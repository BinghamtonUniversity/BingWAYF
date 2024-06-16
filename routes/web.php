<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Saml2Controller;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppController;
use App\HTTP\Middleware\SAML2Authentication;


/* GENERIC STUFF */
Route::middleware(['auth','auth.session'])->group(function () {
    Route::get('/', [AppController::class, 'home']);
    Route::get('/admin/{page?}', [AdminController::class, 'admin']);
});

Route::get('/forcelogin', function (Request $request) {
    Auth::loginUsingId(1,$remember = true);
});

Route::get('/login', [Saml2Controller::class, 'wayf'])->name('login');
Route::any('/idp/demo', [DemoController::class, 'list']);
Route::get('/idp/google', [Saml2Controller::class, 'google_redirect']);
Route::get('/idp/google/callback', [Saml2Controller::class, 'google_callback']);
Route::get('/logout', [Saml2Controller::class, 'logout'])->name('saml_logout');
Route::get('/health', [Saml2Controller::class, 'health'])->name('health_check');

Route::get('/oauth/userinfo', [OAuthController::class, 'userinfo'])->name('openid.userinfo');

/* SAML Stuff */
Route::prefix('/saml2')->group(function () {
    Route::get('/idps/{idp?}',[Saml2Controller::class, 'get_idps']);
    Route::get('/metadata',[Saml2Controller::class, 'metadata'])->name('saml_metadata');
    Route::post('/acs',[Saml2Controller::class, 'acs'])->name('saml_acs');
    Route::get('/sls',[Saml2Controller::class, 'sls'])->name('saml_sls');
    Route::get('/wayf/{id}', [Saml2Controller::class, 'wayfcallback'])->name('saml_wayf');
});

/* OAuth Passport Stuff */
Route::group([
    'as' => 'passport.',
    'prefix' => config('passport.path', 'oauth'),
    'namespace' => '\Laravel\Passport\Http\Controllers',
], function () {
    Route::post('/token', [
        'uses' => 'AccessTokenController@issueToken',
        'as' => 'token',
        'middleware' => 'throttle',
    ]);
    
    Route::get('/authorize', [
        'uses' => 'AuthorizationController@authorize',
        'as' => 'authorizations.authorize',
        'middleware' => ['auth','auth.session','web'],
    ]);
    
    $guard = config('passport.guard', null);
    
    Route::middleware(['web', $guard ? 'auth:'.$guard : 'auth'])->group(function () {
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