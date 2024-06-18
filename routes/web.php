<?php

use Laravel\Passport\Http\Controllers\AccessTokenController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use OpenIDConnect\Laravel\JwksController;
use App\Http\Middleware\SAML2Authentication;
use App\Http\Controllers\Saml2Controller;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IDPController;

/* GENERIC STUFF */
Route::middleware(['auth','auth.session'])->group(function () {
    Route::get('/', [AppController::class, 'home']);
    Route::get('/admin', function (Request $request) {
        return redirect('/admin/users');
    });
    Route::get('/admin/users', [AdminController::class, 'users']);
    Route::get('/admin/idps', [AdminController::class, 'idps']);
    Route::get('/admin/oauth_clients', [AdminController::class, 'oauth_clients']);
    Route::get('/admin/apps', [AdminController::class, 'apps']);

    Route::group(['prefix' => 'api'], function () {
        Route::get('/users',[UserController::class,'get_users']);
        Route::post('/users',[UserController::class,'add_user']);
        Route::put('/users/{user}',[UserController::class,'update_user']);
        Route::delete('/users/{user}',[UserController::class,'delete_user']);
        Route::get('/idps',[IDPController::class,'get_idps']);
        Route::post('/idps',[IDPController::class,'add_idp']);
        Route::put('/idps/{idp}',[IDPController::class,'update_idp']);
        Route::delete('/idps/{idp}',[IDPController::class,'delete_idp']);
        Route::get('/oauth_clients',[OAuthController::class,'get_clients']);
        Route::post('/oauth_clients',[OAuthController::class,'add_client']);
        Route::put('/oauth_clients/{client}',[OAuthController::class,'update_client']);
        Route::delete('/oauth_clients/{client}',[OAuthController::class,'delete_client']);
        Route::delete('/oauth_clients/{client}/regenerate_secret',[OAuthController::class,'regenerate_secret']);
    });
});


Route::get('/forcelogin', function (Request $request) {
    Auth::loginUsingId(1);
});

Route::get('/login', [Saml2Controller::class, 'wayf'])->name('login');
Route::any('/idp/demo', [DemoController::class, 'list']);
Route::get('/idp/google', [Saml2Controller::class, 'google_redirect']);
Route::get('/idp/google/callback', [Saml2Controller::class, 'google_callback']);
Route::get('/logout', [Saml2Controller::class, 'logout'])->name('saml_logout');
Route::get('/health', [Saml2Controller::class, 'health'])->name('health_check');


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
    ->middleware(['allowallorigin']);

Route::group([
    'as' => 'passport.',
    'prefix' => config('passport.path', 'oauth'),
    'namespace' => '\Laravel\Passport\Http\Controllers',
], function () {
    Route::post('/token',[AccessTokenController::class,'issueToken'])
        ->middleware('throttle','allowallorigin')->name('token');           
    
    Route::get('/authorize','AuthorizationController@authorize')
        ->middleware('auth','auth.session','web','allowallorigin')->name('authorizations.authorize');
        
    Route::middleware(['web','auth'])->group(function () {
        Route::post('/token/refresh', [
            'uses' => 'TransientTokenController@refresh',
            'as' => 'token.refresh',
        ]);
    
        Route::post('/authorize', [
            'uses' => 'ApproveAuthorizationController@approve',
            'as' => 'authorizations.approve',
        ]);
    
        Route::delete('/authorize', [
            'uses' => 'DenyAuthorizationController@deny',
            'as' => 'authorizations.deny',
        ]);
    
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

