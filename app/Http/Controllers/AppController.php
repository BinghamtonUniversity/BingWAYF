<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function __construct() {
    }

    public function home(Request $request) {

        return ['user'=>Auth::user()]);
    }

}
