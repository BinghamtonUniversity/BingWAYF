<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserApplication;
use App\Models\Application;

class ApplicationController extends Controller
{
    public function __construct() {
    }
    public function get_applications(Request $request) {
        $applications = Application::get();
        return $applications;
    }
    public function add_application(Request $request) {
        $application = new Application($request->all());
        $application->save();
        return $application;
    }
    public function update_application(Request $request, Application $application) {
        $application->update($request->all());
        return $application;
    }
    public function delete_application(Request $request, Application $application) {
        $application->delete();
        return "1";
    }
    public function get_application_users(Request $request, Application $application) {
        return UserApplication::where('application_id',$application->id)->get();
    }

}