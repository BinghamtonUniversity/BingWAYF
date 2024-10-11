<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserApplication;
use App\Models\Application;
use App\Models\Passport\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function __construct() {
    }

    public function get_applications(Request $request) {
        if (Auth::user()->can('super_admin',User::class)) {
            $applications = Application::get();
        } else {
            $app_ids = UserApplication::select('application_id')
                ->where('user_id',Auth::user()->id)
                ->where('admin',true)
                ->get()->pluck('application_id');
            $applications = Application::whereIn('id',$app_ids)->get();
        }
        return $applications;
    }

    public function add_application(Request $request) {
        $client_id = null;
        $client_secret = null;
        if ($request->auth_type === 'openid') {
            $client_secret = Str::random(40);
            $client = new Client();
            $client->forceFill([
                'user_id' => Auth::user()->id,
                'name' => $request->name,
                'secret' => $client_secret,
                'provider' => null,
                'redirect' => '',
                'personal_access_client' => 0,
                'password_client' => 0,
                'revoked' => 0,
            ]);
            $client->save(); 
            $client_id = $client->id;
            $client_secret = $client->plain_secret;
        } else {
            return response()->json(['error'=>$request->auth_type.' is not supported at this time'],400);
        }

        $application = new Application($request->all());
        $application->auth_client_id = $client_id;
        $application->save();
        $application->secret = $client_secret;

        return $application;
    }

    public function update_application(Request $request, Application $application) {
        if ($application->auth_type !== $request->auth_type) {
            return response()->json(['error'=>'You cannot modify the auth type for an existing application'],400);
        }
        $application->update($request->all());
        return $application;
    }

    public function delete_application(Request $request, Application $application) {
        if ($application->auth_type === 'openid') {
            $client = Client::where('id',$application->auth_client_id)->first();
            if (!is_null($client)) {
                $client->delete();     
            }
        } 
        $application->delete();
        return "1";
    }

    public function get_application_users(Request $request, Application $application) {
        return UserApplication::where('application_id',$application->id)->with('user')->get();
    }
}


