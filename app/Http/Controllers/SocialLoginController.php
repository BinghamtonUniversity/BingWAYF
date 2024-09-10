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

class SocialLoginController extends Controller
{
    public function google_redirect(Request $request) {
        // Construct the RelayState Variable to contain the correct SAML2IDP and Redirect URL
        $relay_state = ['redirect'=>isset(request()->redirect)?request()->redirect:null];
        $relay_state = strtr(base64_encode(json_encode($relay_state)),'+/=','._-');
        
        return Socialite::driver('google')
            ->with(['state' => $relay_state])
            ->redirect();
    }

    public function google_callback(Request $request) {
        $relay_state = json_decode(base64_decode(strtr(request()->state,'._-','+/=')));
        $redirect = null;
        if (!is_null($relay_state) & isset($relay_state->redirect)) { 
            $redirect = $relay_state->redirect;
        }

        $google_user = Socialite::driver('google')->stateless()->user();

        $attributes = [
            'first_name' => $google_user->user['given_name'],
            'last_name' => $google_user->user['family_name'],
            'email' => $google_user->getEmail(),
        ];

        $user_idp = UserIDP::where('unique_id',$attributes['email'])->where('type','google')->first();

        if (!is_null($user_idp)) {
            $user = User::where('id',$user_idp->user_id)->first();
        } else {
            $user = User::where('email',$attributes['email'])->first();
            if (is_null($user)) {
                $user = new User();
                $user->save();
            }
            $user_idp = new UserIDP(['user_id'=>$user->id,'type'=>'google','idp_id'=>null,'unique_id'=>$attributes['email']]);
            $user_idp->save();
        }

        $user_idp->attributes = $google_user->user;
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
            return redirect('/');
        }
    }

    public function microsoft_redirect(Request $request) {
        // Construct the RelayState Variable to contain the correct SAML2IDP and Redirect URL
        $relay_state = ['redirect'=>isset(request()->redirect)?request()->redirect:null];
        $relay_state = strtr(base64_encode(json_encode($relay_state)),'+/=','._-');
        
        return Socialite::driver('microsoft')
            ->with(['state' => $relay_state])
            ->redirect();
    }

    public function microsoft_callback(Request $request) {
        $relay_state = json_decode(base64_decode(strtr(request()->state,'._-','+/=')));
        $redirect = null;
        if (!is_null($relay_state) & isset($relay_state->redirect)) { 
            $redirect = $relay_state->redirect;
        }

        $microsoft_user = Socialite::driver('microsoft')->stateless()->user();
        $attributes = [
            'first_name' => $microsoft_user->user['givenName'],
            'last_name' => $microsoft_user->user['surname'],
            'email' => $microsoft_user->getEmail(),
        ];

        $user_idp = UserIDP::where('unique_id',$attributes['email'])->where('type','microsoft')->first();

        if (!is_null($user_idp)) {
            $user = User::where('id',$user_idp->user_id)->first();
        } else {
            $user = User::where('email',$attributes['email'])->first();
            if (is_null($user)) {
                $user = new User();
                $user->save();
            }
            $user_idp = new UserIDP(['user_id'=>$user->id,'type'=>'microsoft','idp_id'=>null,'unique_id'=>$attributes['email']]);
            $user_idp->save();
        }

        $user_idp->attributes = $microsoft_user->user;
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
            return redirect('/');
        }
    }
}
