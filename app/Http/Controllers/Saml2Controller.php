<?php

namespace App\Http\Controllers;

use App\Libraries\Saml2Auth;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Libraries\SAML2AuthWrapper;
use App\Models\User;
use App\Models\SAML2IDP;
use App\Models\UserIDP;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Socialite;

class Saml2Controller extends Controller
{

    protected $saml2Auth;

    /**
     * @param Saml2Auth $saml2Auth injected.
     */
    function __construct(Saml2Auth $saml2Auth)
    {
        $this->saml2Auth = $saml2Auth;
    }

    public function wayf(Request $request) {
        if(!Auth::user()){
            $contents = view('wayf',['data'=>[
                'redirect'=> urlencode($request->redirect),
                'enabled_idps' => SAML2IDP::select('name','id','enabled')
                    ->where('enabled',true)
                    ->orderBy('order','asc')->orderBy('id','asc')->get()
            ]]);
            return response($contents)
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');        
        } else {
            return redirect('/');
        }  
    }

    public function get_idps(Request $request, SAML2IDP $idp = null) {
        if (!is_null($idp)) {
            return $idp;
        } else {
            return SAML2IDP::select('name','id','enabled')
                ->orderBy('order','asc')->orderBy('id','asc')->get();
        }
    }

    public function wayfcallback($id) {
        if(!Auth::user()){
            $idp = SAML2IDP::where('id',$id)->first();
            config(['saml2_settings.idp' => [
                'name' => $idp->name,
                'entityId' => $idp->entityId,
                'singleSignOnService' => ['url'=>$idp->singleSignOnServiceUrl],
                'singleLogoutService' => ['url'=>$idp->singleLogoutServiceUrl],
                'x509cert' => $idp->x509cert,
                'data_map' => $idp->config
            ]]);
            $this->saml2Auth->configure();
            // Construct the RelayState Variable to contain the correct SAML2IDP and Redirect URL
            $relay_state = ['idp'=>$id,'redirect'=>isset(request()->redirect)?request()->redirect:null];
            $relay_state = strtr(base64_encode(json_encode($relay_state)),'+/=','._-');
            return $this->saml2Auth->login($relay_state);
        } else {
            return redirect('/');
        }  
    }

    /**
     * Generate local sp metadata
     * @return \Illuminate\Http\Response
     */
    public function metadata() {
        $metadata = $this->saml2Auth->getMetadata();
        return response($metadata, 200, ['Content-Type' => 'text/xml']);
    }

    /**
     * Process an incoming saml2 assertion request.
     * Fires 'Saml2LoginEvent' event if a valid user is Found
     */
    public function acs() {
        // Deconstruct the RelayState Variable to fetch the correct SAML2IDP and Redirect URL
        $relay_state = json_decode(base64_decode(strtr(request()->input('RelayState'),'._-','+/=')));
        if (is_null($relay_state) || !isset($relay_state->idp) || is_null($relay_state->idp)) {
            return redirect('login');
        }

        $id = $relay_state->idp;
        $redirect = $relay_state->redirect;
        
        $idp = SAML2IDP::where('id',$id)->first();
        if (is_null($idp)) {
            return redirect('login');
        }

        config(['saml2_settings.idp' => [
            'name' => $idp->name,
            'entityId' => $idp->entityId,
            'singleSignOnService' => ['url'=>$idp->singleSignOnServiceUrl],
            'singleLogoutService' => ['url'=>$idp->singleLogoutServiceUrl],
            'x509cert' => $idp->x509cert,
            'data_map' => $idp->config
        ]]);

        $this->saml2Auth->configure();

        // try {
            $errors = $this->saml2Auth->acs();
        // } catch(\Exception $e) {
            // continue?
        // }

        if (!empty($errors)) {
            var_dump($errors); var_dump($this->saml2Auth->getLastErrorReason()); exit();
            logger()->error('Saml2 error_detail', ['error' => $this->saml2Auth->getLastErrorReason()]);
            session()->flash('saml2_error_detail', [$this->saml2Auth->getLastErrorReason()]);

            logger()->error('Saml2 error', $errors);
            session()->flash('saml2_error', $errors);
            return redirect(config('saml2_settings.errorRoute'));
        }
        
        $saml2user = $this->saml2Auth->getSaml2User();

        $messageId = $this->saml2Auth->getLastMessageId();
        // your own code preventing reuse of a $messageId to stop replay attacks
        $saml_attributes = ['id'=>$saml2user->getUserId()];
        foreach($saml2user->getAttributesWithFriendlyName() as $attribute_name => $attribute_value) {
            if (isset($attribute_value[0])) {
                $saml_attributes[$attribute_name] = $attribute_value[0];
            }
        }
        if ($idp->debug === true) {
            echo '<h3>SAML Attributes</h3>';
            echo '<pre>'.print_r($saml_attributes,true).'</pre>';
            echo '<h3>SAML2 User</h3>';
            echo '<pre>'.print_r($saml2user,true).'</pre>';
            echo '<h3>SAML Last Message</h3>';
            echo '<pre>'.print_r($messageId,true).'</pre>';
            exit();
        }

        $data_map = [
            'unique_id' => '{{mail}}',
            'first_name' => '{{givenName}}',
            'last_name' => '{{sn}}',
            'email' => '{{mail}}',
        ];

        $m = new \Mustache_Engine;
        $attributes = [
            'unique_id' => $m->render($data_map['unique_id'], $saml_attributes),
            'first_name' => $m->render($data_map['first_name'], $saml_attributes),
            'last_name' => $m->render($data_map['last_name'], $saml_attributes),
            'email' => $m->render($data_map['email'], $saml_attributes),
        ];

        $user_idp = UserIDP::where('unique_id',$attributes['unique_id'])
            ->where('type','saml2')
            ->where('idp_id',$id)->first();
        
        if (!is_null($user_idp)) {
            $user = User::where('id',$user_idp->user_id)->first();
        } else {
            $user = User::where('email',$attributes['unique_id'])->first();
            if (is_null($user)) {
                $user = new User();
                $user->save();
            }
            $user_idp = new UserIDP(['user_id'=>$user->id,'type'=>'saml2','idp_id'=>$id,'unique_id'=>$attributes['unique_id']]);
            $user_idp->save();
        }

        $user_idp->attributes = $saml_attributes;
        $user_idp->last_login = now();

        $user_idp->save();
        $user->first_name = $attributes['first_name'];
        $user->last_name = $attributes['last_name'];
        $user->email = $attributes['email'];
        $user->save();

        Auth::login($user);

        if ($redirect !== null) {
            return redirect($redirect);
        } else {
            return redirect(config('saml2_settings.loginRoute'));
        }
    }

    /**
     * Process an incoming saml2 logout request.
     * Fires 'saml2.logoutRequestReceived' event if its valid.
     * This means the user logged out of the SSO infrastructure, you 'should' log him out locally too.
     */
    public function sls() {
        $error = $this->saml2Auth->sls(config('saml2_settings.retrieveParametersFromServer'));
        if (!empty($error)) {
            throw new \Exception("Could not log out");
        }

        return redirect(config('saml2_settings.logoutRoute')); //may be set a configurable default
    }

    /**
     * This initiates a logout request across all the SSO infrastructure.
     */
    public function logout(Request $request) {
        if (Auth::check()) {
            Auth::user()->force_logout();
            Auth::logout();
        }
        Session::save();    
        $returnTo = $request->query('returnTo');
        $sessionIndex = $request->query('sessionIndex');
        $nameId = $request->query('nameId');
        $full_logout = true;
        return view('logout',['full_logout' => $full_logout]);
    }

    public function idps(Request $request) {
        $idps = [];
        $idps[] = ['value'=>null,'label'=>'None'];
        $idps[] = ['value'=>'google','label'=>'Google'];
        $allidps = SAML2IDP::get();
        foreach($allidps as $idp) {
            $idps[] = ['value'=>$idp->id, 'label'=>$idp->name];
        }
        return $idps;
    }

    public function health(Request $request) {
        return response('OK',200)->header('Content-Type', 'text/plain');
    }


}
