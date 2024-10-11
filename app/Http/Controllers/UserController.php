<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserIDP;
use App\Models\UserApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct() {
    }
    public function get_users(Request $request) {
        $users = User::select('id','first_name','last_name','email','admin')->get();
        return $users;
    }
    public function add_user(Request $request) {
        $user = new User($request->all());
        $user->save();
        return $user;
    }
    public function update_user(Request $request, User $user) {
        $user->update($request->all());
        return $user;
    }
    public function delete_user(Request $request, User $user) {
        $user->delete();
        return "1";
    }
    public function get_user_idps(Request $request, User $user) {
        return UserIDP::where('user_id',$user->id)->get();
    }
    public function add_user_idp(Request $request, User $user) {
        $user_idp = new UserIDP([
            'idp_id' => $request->idp_id,
            'public' => $request->public,
            'user_id' => $user->id,
        ]);
        $user_idp->save();
        return $user_idp;
    }
    public function update_user_idp(Request $request, User $user, UserIDP $user_idp) {
        $user_idp->public = $request->public;
        $user_idp->save();
        return $user_idp;
    }
    public function delete_user_idp(Request $request, User $user, UserIDP $user_idp) {
        $user_idp->delete();
        return "1";
    }
    public function impersonate(Request $request, User $user) {
        Auth::login($user);
        return "1";
    }
    public function get_user_applications(Request $request, User $user) {
        return UserApplication::where('user_id',$user->id)->get();
    }
    public function add_user_application(Request $request, User $user) {
        $user_application = new UserApplication([
            'application_id' => $request->application_id,
            'approved' => $request->approved,
            'admin' => $request->admin,
            'user_id' => $user->id,
        ]);
        $user_application->save();
        return $user_application;
    }
    public function update_user_application(Request $request, User $user, UserApplication $user_application) {
        $user_application->approved = $request->approved;
        $user_application->admin = $request->admin;
        $user_application->save();
        if (!$user_application->approved) {
            $user->force_logout($user_application);
        }
        return $user_application;
    }
    public function delete_user_application(Request $request, User $user, UserApplication $user_application) {
        $user->force_logout($user_application);
        $user_application->delete();
        return "1";
    }
    public function search($search_string='') {
        $search_elements_parsed = preg_split('/[\s,]+/',strtolower($search_string));
        $search = []; $users = []; $ids = collect();
        if (count($search_elements_parsed) === 1 && $search_elements_parsed[0]!='') {
            $search[0] = $search_elements_parsed[0];
            $ids = $ids->merge(DB::table('users')->select('id')
                ->orWhere('id',$search[0])->limit(15)->get()->pluck('id'));
            $ids = $ids->merge(DB::table('users')->select('id')
                ->orWhere('first_name','like',$search[0].'%')
                ->orWhere('last_name','like',$search[0].'%')->get()->pluck('id'));
            $ids = $ids->merge(DB::table('users')->select('id')
                ->orWhere('email',$search[0])->limit(15)->get()->pluck('id'));
            $users = User::select('id','first_name','last_name','email')
                ->whereIn('id',$ids)->orderBy('first_name', 'asc')->orderBy('last_name', 'asc')
                ->limit(15)->get()->toArray();
        } else if (count($search_elements_parsed) > 1) {
            $search[0] = $search_elements_parsed[0];
            $search[1] = $search_elements_parsed[count($search_elements_parsed)-1];
            $ids = $ids->merge(DB::table('users')->select('id')
                ->where('first_name','like',$search[0].'%')->where('last_name','like',$search[1].'%')
                ->limit(15)->get()->pluck('id'));
            $ids = $ids->merge(DB::table('users')->select('id')
                ->where('first_name','like',$search[1].'%')->where('last_name','like',$search[0].'%')
                ->limit(15)->get()->pluck('id'));
            $users = User::select('id','first_name','last_name','email')
                ->whereIn('id',$ids)->orderBy('first_name', 'asc')->orderBy('last_name', 'asc')
                ->limit(15)->get()->toArray();
        }
        foreach($users as $index => $user) {
            $users[$index] = array_intersect_key($user, array_flip(['id','first_name','last_name','email']));
        }
        return $users;
    }
}
