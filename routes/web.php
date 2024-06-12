<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Saml2Controller;
use App\Http\Controllers\AdminController;
use App\HTTP\Middleware\SAML2Authentication;

/* GENERIC STUFF */
// Route::group(['middleware'=>['saml2.auth']], function () use ($router) {
Route::middleware([SAML2Authentication::class])->group(function () {
    Route::get('/', [AppController::class, 'home']);
    Route::get('/admin/{page?}', [AdminController::class, 'admin']);
});

Route::get('/login', [Saml2Controller::class, 'wayf'])->name('wayf_login');
Route::any('/idp/demo', [DemoController::class, 'list']);
Route::get('/idp/google', [Saml2Controller::class, 'google_redirect']);
Route::get('/idp/google/callback', [Saml2Controller::class, 'google_callback']);
Route::get('/logout', [Saml2Controller::class, 'logout'])->name('saml_logout');
Route::get('/health', [Saml2Controller::class, 'health'])->name('health_check');

/* SAML Stuff */
Route::prefix('/saml2')->group(function () {
    Route::get('/metadata',[Saml2Controller::class, 'metadata'])->name('saml_metadata');
    Route::post('/acs',[Saml2Controller::class, 'acs'])->name('saml_acs');
    Route::get('/sls',[Saml2Controller::class, 'sls'])->name('saml_sls');
    Route::get('/wayf/{id}', [Saml2Controller::class, 'wayfcallback'])->name('saml_wayf');
});
