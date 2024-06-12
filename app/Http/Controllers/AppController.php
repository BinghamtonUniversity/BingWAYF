<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserIDP;


class AppController extends Controller
{
    public function __construct() {
    }

    public function home(Request $request) {
        $info = UserIDP::where('user_id',Auth::user()->id)->with('idp')->get();
        // return $info;
        return view('welcome',['info'=>$info]);
    }

}
