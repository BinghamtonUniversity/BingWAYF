<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserIDP;
use App\Entities\IdentityEntity;
use App\Models\Passport\Client;

class OAuthController extends Controller
{
    public function __construct() {
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
        $clients = Client::where('revoked',0)->get();
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
