<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserIDP;


class DashboardController extends Controller
{
    public function __construct() {
    }

    public function home(Request $request) {
        $info = UserIDP::where('user_id',Auth::user()->id)->with('idp')->get();
        return view('welcome',['data'=>['info'=>$info,'user'=>Auth::user()]]);
    }

}
