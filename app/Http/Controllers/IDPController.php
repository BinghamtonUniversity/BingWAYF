<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserIDP;
use App\Models\SAML2IDP;

class IDPController extends Controller
{
    public function __construct() {
    }
    public function get_idps(Request $request) {
        $idps = SAML2IDP::select('id','name','entityId','singleSignOnServiceUrl','singleLogoutServiceUrl','enabled','debug','order')->get();
        return $idps;
    }
    public function add_idp(Request $request) {
        $idp = new SAML2IDP($request->all());
        $idp->save();
        return $idp;
    }
    public function update_idp(Request $request, SAML2IDP $idp) {
        $idp->update($request->all());
        return $idp;
    }
    public function delete_idp(Request $request, SAML2IDP $idp) {
        $idp->delete();
        return "1";
    }
    public function get_idp_users(Request $request, SAML2IDP $idp) {
        return UserIDP::where('idp_id',$idp->id)->where('type','saml2')->get();
    }
}