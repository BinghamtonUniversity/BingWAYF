<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserIDP;
use App\Models\IDP;

class IDPController extends Controller
{
    public function __construct() {
    }
    public function get_idps(Request $request) {
        $idps = IDP::select('id','entityId','singleSignOnServiceUrl','singleLogoutServiceUrl','enabled','debug')->get();
        return $idps;
    }
    public function add_idp(Request $request) {
        $idp = new IDP($request->all());
        $idp->save();
        return $idp;
    }
    public function update_idp(Request $request, IDP $idp) {
        $idp->update($request->all());
        return $idp;
    }
    public function delete_idp(Request $request, IDP $idp) {
        $idp->delete();
        return "1";
    }
}