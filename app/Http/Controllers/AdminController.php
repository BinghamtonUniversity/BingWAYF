<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\IDP;

class AdminController extends Controller
{
    public function __construct() {
    }

    public function users(Request $request, $page=null) {
        $users = User::select('id','first_name','last_name','email')->get();
        $identity = Auth::user();
        $actions = [
            ["name"=>"create","label"=>"New User"],'',
            ["name"=>"edit","label"=>"Update User"],'',
            ["name"=>"delete","label"=>"Delete User"]
        ];
        return view('default.admin',[
            'page'=>'users',
            'records'=>$users,
            'title'=>'Manage Users',
            'help'=>'Manage the users and whatnot',
            'actions'=>$actions,
        ]);
    }
    public function idps(Request $request, $page=null) {
        $idps = IDP::get();
        $identity = Auth::user();
        $actions = [
            ["name"=>"create","label"=>"New IDP"],'',
            ["name"=>"edit","label"=>"Update IDP"],'',
            ["name"=>"delete","label"=>"Delete IDP"]
        ];
        return view('default.admin',[
            'page'=>'idps',
            'records'=>$idps,
            'title'=>'Manage IDPs',
            'help'=>'Manage the idps and whatnot',
            'actions'=>$actions,
        ]);
    }
    public function oauth(Request $request, $page=null) {
    }
    public function apps(Request $request, $page=null) {
    }

}