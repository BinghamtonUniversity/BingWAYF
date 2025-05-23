<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\SAML2IDP;
use App\Models\Application;

class AdminController extends Controller
{
    public function __construct() {
    }

    public function users(Request $request) {
        $identity = Auth::user();
        $actions = [
            ["name"=>"create","label"=>"New User"],'',
            ["name"=>"edit","label"=>"Update User"],
            ["label"=>"Manage Applications","name"=>"manage_applications","min"=>1,"max"=>1,"type"=>"default"],
            ["label"=>"Manage Groups","name"=>"manage_groups","min"=>1,"max"=>1,"type"=>"default"],
            ["label"=>"View IDPs","name"=>"view_idps","min"=>1,"max"=>1,"type"=>"default"],
            ["label"=>"Impersonate","name"=>"impersonate","min"=>1,"max"=>1,"type"=>"danger"],'',
            ["name"=>"delete","label"=>"Delete User"]
        ];
        return view('default.admin',[
            'page'=>'users',
            'title'=>'Manage Users',
            'help'=>'Manage the users and whatnot',
            'actions'=>$actions,
        ]);
    }

    public function user_idps(Request $request, User $user) {
        $identity = Auth::user();
        $actions = [];
        return view('default.admin',[
            'id'=>$user->id,
            'page'=>'users_idps',
            'title'=>'Manage '.$user->first_name."'s".' IDPs',
            'help'=>'Manage the IDPs and whatnot',
            'actions'=>$actions,
        ]);
    }

    public function user_applications(Request $request, User $user) {
        $identity = Auth::user();
        $actions = [
            ["name"=>"create","label"=>"Add Application to User"],'',
            ["name"=>"edit","label"=>"Update User Application"],'',
            ["name"=>"delete","label"=>"Remove Application from User"]
        ];
        return view('default.admin',[
            'id'=>$user->id,
            'page'=>'users_applications',
            'title'=>'Manage '.$user->first_name."'s".' Applications',
            'help'=>'Manage the Applications and whatnot',
            'actions'=>$actions,
        ]);
    }

    public function user_groups(Request $request, User $user) {
        $identity = Auth::user();
        $actions = [
            ["name"=>"create","label"=>"Add Group to User"],'',
            ["name"=>"delete","label"=>"Remove Group from User"]
        ];
        return view('default.admin',[
            'id'=>$user->id,
            'page'=>'users_groups',
            'title'=>'Manage '.$user->first_name."'s".' Groups',
            'help'=>'Manage the Groups and whatnot',
            'actions'=>$actions,
        ]);
    }

    public function groups(Request $request) {
        $identity = Auth::user();
        $actions = [
            ["name"=>"create","label"=>"New Group"],'',
            ["name"=>"edit","label"=>"Update Group"],
            ["label"=>"Manage Users","name"=>"manage_users","min"=>1,"max"=>1,"type"=>"default"],
            ["name"=>"delete","label"=>"Delete Group"]
        ];
        return view('default.admin',[
            'page'=>'groups',
            'title'=>'Manage Groups',
            'help'=>'Manage the groups and whatnot',
            'actions'=>$actions,
        ]);
    }

    public function group_users(Request $request, Group $group) {
        $identity = Auth::user();
        $actions = [
            ["name"=>"create","label"=>"Add User to Group"],'',
            ["name"=>"delete","label"=>"Remove User from Group"]
        ];
        return view('default.admin',[
            'id'=>$group->id,
            'page'=>'groups_users',
            'title'=>'Manage '.$group->name."'s".' Users',
            'help'=>'Manage the users and whatnot',
            'actions'=>$actions,
        ]);
    }

    public function idps(Request $request) {
        $identity = Auth::user();
        $actions = [
            ["name"=>"create","label"=>"New IDP"],'',
            ["name"=>"edit","label"=>"Update IDP"],
            ["label"=>"View Users","name"=>"view_users","min"=>1,"max"=>1,"type"=>"default"],'',
            ["name"=>"delete","label"=>"Delete IDP"]
        ];
        return view('default.admin',[
            'page'=>'idps',
            'title'=>'Manage IDPs',
            'help'=>'Manage the idps and whatnot',
            'actions'=>$actions,
        ]);
    }

    public function idp_users(Request $request, SAML2IDP $idp) {
        $identity = Auth::user();
        $actions = [];
        return view('default.admin',[
            'id'=>$idp->id,
            'page'=>'idps_users',
            'title'=>'Manage '.$idp->name.' Users',
            'help'=>'Manage the Users and whatnot',
            'actions'=>$actions,
        ]);
    }

    public function applications(Request $request) {
        $identity = Auth::user();
        $actions = [
            ["name"=>"create","label"=>"New Application"],'',
            ["name"=>"edit","label"=>"Update Application"],
            ["label"=>"Manage Users","name"=>"manage_users","min"=>1,"max"=>1,"type"=>"default"],'',
            ["name"=>"delete","label"=>"Delete Application"]
        ];
        return view('default.admin',[
            'page'=>'applications',
            'title'=>'Manage Applications',
            'help'=>'Manage the apps and whatnot',
            'actions'=>$actions,
        ]);
    }

    public function application_users(Request $request, Application $application) {
        $identity = Auth::user();
        $actions = [
            ["name"=>"create","label"=>"Add User to Application"],'',
            ["name"=>"edit","label"=>"Update Application User"],'',
            ["name"=>"delete","label"=>"Remove User from Application"]
        ];
        return view('default.admin',[
            'id'=>$application->id,
            'page'=>'applications_users',
            'title'=>'Manage '.$application->name.' Users',
            'help'=>'Manage the Users and whatnot',
            'actions'=>$actions,
        ]);
    }

    public function oauth_clients(Request $request) {
        $identity = Auth::user();
        $actions = [
            ["name"=>"create","label"=>"New OAuth Client"],'',
            ["name"=>"edit","label"=>"Update OAuth Client"],'',
            ["name"=>"delete","label"=>"Delete OAuth Client"]
        ];
        return view('default.admin',[
            'page'=>'oauth_clients',
            'title'=>'Manage OpenID and OAuth Clients',
            'help'=>'Manage the OAuth and OpenID Client and whatnot',
            'actions'=>$actions,
        ]);
    }

}