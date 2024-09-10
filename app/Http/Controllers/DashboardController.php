<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserIDP;
use App\Models\UserApplication;
use App\Models\Application;
use App\Entities\IdentityEntity;

class DashboardController extends Controller
{
    public function __construct() {
    }

    public function home(Request $request) {
        $user_idps = UserIDP::where('user_id',Auth::user()->id)->with('saml2_idp')->get();
        $user_apps = UserApplication::where('user_id',Auth::user()->id)->with('application')->get();
        $all_apps = Application::get();

        $identityEntity = new IdentityEntity();
        $identityEntity->setIdentifier(Auth::user()->id);
        $claims = $identityEntity->getClaims();

        return view('welcome',['data'=>[
            'user_idps'=>$user_idps,
            'user_apps'=>$user_apps,
            'all_apps'=>$all_apps,
            'claims'=>$claims,
            'user'=>Auth::user()
        ]]);
    }

}
