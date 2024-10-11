<?php

use Laravel\Passport\Http\Controllers\AccessTokenController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use OpenIDConnect\Laravel\JwksController;
use App\Http\Middleware\SAML2Authentication;
use App\Http\Controllers\Saml2Controller;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IDPController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\SocialLoginController;
use App\Models\User;

/* GENERIC STUFF */
Route::middleware(['auth','auth.session'])->group(function () {
    Route::get('/', [DashboardController::class, 'home']);

    Route::group(['prefix' => 'admin'], function () {
        Route::get('/', function (Request $request) {
            return redirect('/admin/applications');
        })->can('app_admin',User::class);
        Route::get('/users', [AdminController::class, 'users'])->can('super_admin',User::class);
        Route::get('/users/{user}/idps', [AdminController::class, 'user_idps'])->can('super_admin',User::class);
        Route::get('/users/{user}/applications', [AdminController::class, 'user_applications'])->can('super_admin',User::class);
        Route::get('/idps', [AdminController::class, 'idps'])->can('super_admin',User::class);
        Route::get('/idps/{idp}/users', [AdminController::class, 'idp_users'])->can('super_admin',User::class);
        Route::get('/oauth_clients', [AdminController::class, 'oauth_clients'])->can('super_admin',User::class);
        Route::get('/applications', [AdminController::class, 'applications'])->can('app_admin',User::class);
        Route::get('/applications/{application}/users', [AdminController::class, 'application_users'])->can('app_admin',User::class);    
    });

    Route::group(['prefix' => 'api'], function () {
        Route::get('/users',[UserController::class,'get_users'])->can('app_admin',User::class);
        Route::post('/users',[UserController::class,'add_user'])->can('app_admin',User::class);
        Route::put('/users/{user}',[UserController::class,'update_user'])->can('super_admin',User::class);
        Route::delete('/users/{user}',[UserController::class,'delete_user'])->can('super_admin',User::class);
        Route::get('/users/{user}/idps',[UserController::class,'get_user_idps'])->can('super_admin',User::class);
        Route::post('/users/{user}/idps',[UserController::class,'add_user_idp'])->can('super_admin',User::class);
        Route::put('/users/{user}/idps/{user_idp}',[UserController::class,'update_user_idp'])->can('super_admin',User::class);
        Route::delete('/users/{user}/idps/{user_idp}',[UserController::class,'delete_user_idp'])->can('super_admin',User::class);
        Route::post('/users/{user}/impersonate',[UserController::class,'impersonate'])->can('super_admin',User::class);
        Route::get('/users/{user}/applications',[UserController::class,'get_user_applications'])->can('super_admin',User::class);
        Route::post('/users/{user}/applications',[UserController::class,'add_user_application'])->can('super_admin',User::class);
        Route::put('/users/{user}/applications/{user_application}',[UserController::class,'update_user_application'])->can('app_admin',User::class);
        Route::delete('/users/{user}/applications/{user_application}',[UserController::class,'delete_user_application'])->can('app_admin',User::class);
        Route::get('/users/search/{search_string?}',[UserController::class,'search'])->can('super_admin',User::class);
        
        Route::get('/idps',[IDPController::class,'get_idps'])->can('super_admin',User::class);
        Route::post('/idps',[IDPController::class,'add_idp'])->can('super_admin',User::class);
        Route::put('/idps/{idp}',[IDPController::class,'update_idp'])->can('super_admin',User::class);
        Route::delete('/idps/{idp}',[IDPController::class,'delete_idp'])->can('super_admin',User::class);
        Route::get('/idps/{idp}/users',[IDPController::class,'get_idp_users'])->can('super_admin',User::class);
        
        Route::get('/applications',[ApplicationController::class,'get_applications'])->can('app_admin',User::class);
        Route::post('/applications',[ApplicationController::class,'add_application'])->can('super_admin',User::class);
        Route::put('/applications/{application}',[ApplicationController::class,'update_application'])->can('app_admin',User::class);
        Route::delete('/applications/{application}',[ApplicationController::class,'delete_application'])->can('app_admin',User::class);
        Route::get('/applications/{application}/users',[ApplicationController::class,'get_application_users'])->can('app_admin',User::class);

        Route::get('/oauth_clients',[OAuthController::class,'get_clients'])->can('super_admin',User::class);
        Route::post('/oauth_clients',[OAuthController::class,'add_client'])->can('super_admin',User::class);
        Route::put('/oauth_clients/{client}',[OAuthController::class,'update_client'])->can('super_admin',User::class);
        Route::delete('/oauth_clients/{client}',[OAuthController::class,'delete_client'])->can('super_admin',User::class);
        Route::delete('/oauth_clients/{client}/regenerate_secret',[OAuthController::class,'regenerate_secret'])->can('super_admin',User::class);
    });
});

Route::get('/forcelogin', function (Request $request) {
    Auth::loginUsingId(1);
});

Route::get('/login', [Saml2Controller::class, 'wayf'])->name('login');
Route::get('/logout', [Saml2Controller::class, 'logout'])->name('saml_logout');
Route::get('/health', [Saml2Controller::class, 'health'])->name('health_check');
Route::get('/idp/google', [SocialLoginController::class, 'google_redirect']);
Route::get('/idp/google/callback', [SocialLoginController::class, 'google_callback']);
Route::get('/idp/microsoft', [SocialLoginController::class, 'microsoft_redirect']);
Route::get('/idp/microsoft/callback', [SocialLoginController::class, 'microsoft_callback']);

/* SAML Stuff */
Route::prefix('/saml2')->group(function () {
    Route::get('/idps/{idp?}',[Saml2Controller::class, 'get_idps']);
    Route::get('/metadata',[Saml2Controller::class, 'metadata'])->name('saml_metadata');
    Route::post('/acs',[Saml2Controller::class, 'acs'])->name('saml_acs');
    Route::get('/sls',[Saml2Controller::class, 'sls'])->name('saml_sls');
    Route::get('/wayf/{id}', [Saml2Controller::class, 'wayfcallback'])->name('saml_wayf');
});

/* OAuth Passport Stuff */
Route::get('/.well-known/openid-configuration', [OAuthController::class, 'openid_discovery'])
    ->name('openid.discovery')
    ->middleware('allowallorigin');
Route::get('/oauth/jwks', JwksController::class)
    ->name('openid.jwks')
    ->middleware(['allowallorigin']);
Route::get('/oauth/profile', [OAuthController::class, 'profile'])
    ->name('openid.userinfo')
    ->middleware(['allowallorigin','auth:api']);
Route::post('/oauth/profile', [OAuthController::class, 'profile'])
    ->middleware(['allowallorigin','auth:api']);

Route::group([
    'as' => 'passport.',
    'prefix' => config('passport.path', 'oauth'),
    'namespace' => '\Laravel\Passport\Http\Controllers',
], function () {
    Route::post('/token',[AccessTokenController::class,'issueToken'])
        ->middleware('throttle','allowallorigin')->name('token');           

    Route::get('/authorize','AuthorizationController@authorize')
        ->middleware('auth','auth.session','web','allowallorigin')
        ->name('authorizations.authorize');
        
    Route::middleware(['web','auth'])->group(function () {
        Route::post('/token/refresh', [
            'uses' => 'TransientTokenController@refresh',
            'as' => 'token.refresh',
        ]);
    
        // Don't allow users to self authorize!
        // Route::post('/authorize', [
        //     'uses' => 'ApproveAuthorizationController@approve',
        //     'as' => 'authorizations.approve',
        // ]);
    
        // Route::delete('/authorize', [
        //     'uses' => 'DenyAuthorizationController@deny',
        //     'as' => 'authorizations.deny',
        // ]);
    
        Route::get('/tokens', [
            'uses' => 'AuthorizedAccessTokenController@forUser',
            'as' => 'tokens.index',
        ]);
    
        Route::delete('/tokens/{token_id}', [
            'uses' => 'AuthorizedAccessTokenController@destroy',
            'as' => 'tokens.destroy',
        ]);
    
        Route::get('/clients', [
            'uses' => 'ClientController@forUser',
            'as' => 'clients.index',
        ]);
    
        Route::post('/clients', [
            'uses' => 'ClientController@store',
            'as' => 'clients.store',
        ]);
    
        Route::put('/clients/{client_id}', [
            'uses' => 'ClientController@update',
            'as' => 'clients.update',
        ]);
    
        Route::delete('/clients/{client_id}', [
            'uses' => 'ClientController@destroy',
            'as' => 'clients.destroy',
        ]);
    
        Route::get('/scopes', [
            'uses' => 'ScopeController@all',
            'as' => 'scopes.index',
        ]);
    
        Route::get('/personal-access-tokens', [
            'uses' => 'PersonalAccessTokenController@forUser',
            'as' => 'personal.tokens.index',
        ]);
    
        Route::post('/personal-access-tokens', [
            'uses' => 'PersonalAccessTokenController@store',
            'as' => 'personal.tokens.store',
        ]);
    
        Route::delete('/personal-access-tokens/{token_id}', [
            'uses' => 'PersonalAccessTokenController@destroy',
            'as' => 'personal.tokens.destroy',
        ]);
    });
});

