<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\GroupMember;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    public function __construct() {
    }
    public function get_groups(Request $request) {
        $groups = Group::select('id','slug','name','description')->get();
        return $groups;
    }
    public function get_groups_users(Request $request, Group $group) {
        return GroupMember::where('group_id',$group->id)->with('user')->get();
    }
    public function add_group(Request $request) {
        $group = new Group($request->all());
        $group->save();
        return $group;
    }
    public function update_group(Request $request, Group $group) {
        $group->update($request->all());
        return $group;
    }
    public function delete_group(Request $request, Group $group) {
        GroupMember::where('group_id',$group->id)->delete();
        $group->delete();
        return "1";
    }

}
