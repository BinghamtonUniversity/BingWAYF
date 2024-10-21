<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\UserIDP;
use App\Models\UserApplication;
use App\Models\Application;
use App\Models\Passport\Client;
use App\Entities\IdentityEntity;

class OAuthController extends Controller
{
    public function __construct() {
    }

    public function request_access(Request $request, Client $client) {
        $application = Application::where('auth_type','openid')
            ->where('auth_client_id',$client->id)->first();
        if (is_null($application)) {
            return response()->json(['error' => 'Application does not exist!'],403);
        }
        $user_ids = UserApplication::where('application_id',$application->id)
            ->where('admin',true)
            ->where('approved',true)
            ->select('user_id')
            ->get()->pluck('user_id');
        $users = User::whereIn('id',$user_ids)
            ->select('first_name','last_name','email')
            ->get();
        if (count($user_ids) === 0) {
            return response()->json(['error' => 'No Admins for this App!'],403);
        }

        $body = 'You are receieving this email because you are an administrator of the "'.
        $application->name.'" application.'."\n\n".
        Auth::user()->first_name.' '.Auth::user()->last_name.' <'.Auth::user()->email.'>'.
        ' has requested access to this application.'."\n\n".
        'To address this request, please visit: '.
        config('app.url').'/admin/applications/'.$application->id.'/users';

        Mail::raw($body, function($message) use ($users,$application) { 
            $message->from('noreply@binghamton.edu','BingWAYF');
            $message->replyTo(Auth::user()->email,Auth::user()->first_name.' '.Auth::user()->last_name);
            foreach($users as $user) {
                $message->to($user->email,$user->first_name.' '.$user->last_name);
            }
            $message->subject('BingWAYF "'.$application->name.'" Access Request'); 
        });

        return ['success' => 'Email Sent!'];
    }

    public function profile(Request $request) {
        if (!is_null($request->user())) {
            $userid = $request->user()->getAuthIdentifier();
        } else if (!is_null(Auth::user())) {
            $userid = Auth::user()->id;
        } else {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $identityEntity = new IdentityEntity();
        $identityEntity->setIdentifier($userid);
        return $identityEntity->getClaims();
    }

    public function openid_discovery(Request $request) {
        $response = [
            'issuer' => url('/'),
            'authorization_endpoint' => route('passport.authorizations.authorize'),
            'token_endpoint' => route('passport.token'),
            'jwks_uri' => route('openid.jwks'),
            'response_types_supported' => [
                'code',
                'token',
                'id_token',
                'code token',
                'code id_token',
                'token id_token',
                'code token id_token',
                'none',
            ],
            'subject_types_supported' => [
                'public',
            ],
            'id_token_signing_alg_values_supported' => [
                'RS256',
            ],
            'scopes_supported' => array_keys(config('openid.passport.tokens_can')),
            'token_endpoint_auth_methods_supported' => [
                'client_secret_basic',
                'client_secret_post',
            ],
        ];

        if (Route::has('openid.userinfo')) {
            $response['userinfo_endpoint'] = route('openid.userinfo');
        }

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    public function get_clients(Request $request) {
        $clients = DB::table('oauth_clients')
            ->select('id','name','secret','redirect')
            ->where('revoked',0)->get();
        return $clients;
    }

    public function add_client(Request $request) {
        $client = new Client();
        $client->forceFill([
            'user_id' => Auth::user()->id,
            'name' => $request->name,
            'secret' => Str::random(40),
            'provider' => null,
            'redirect' => $request->name,
            'personal_access_client' => 0,
            'password_client' => 0,
            'revoked' => 0,
        ]);
        $client->save();
        return $client->plain_secret;
    }
    public function update_client(Request $request, Client $client) {
        $client->forceFill([
            'name' => $request->name, 'redirect' => $request->redirect,
        ])->save();
        return $client;
    }
    public function delete_client(Request $request, Client $client) {
        $client->tokens()->update(['revoked' => true]);
        $client->forceFill(['revoked' => true])->save();
        return "1";
    }
    public function regenerate_secret(Request $request, Client $client) {
        $client->forceFill([
            'secret' => Str::random(40),
        ])->save();
        return $client;
    }

}
