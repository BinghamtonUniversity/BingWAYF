<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserIDP;

class UserController extends Controller
{
    public function __construct() {
    }
    public function get_users(Request $request) {
        $users = User::select('id','first_name','last_name','email')->get();
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
}