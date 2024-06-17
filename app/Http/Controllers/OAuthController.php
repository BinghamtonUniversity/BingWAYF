<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserIDP;
use App\Entities\IdentityEntity;

class OAuthController extends Controller
{
    public function __construct() {
    }

    public function profile(Request $request) {
        if (!is_null($request->user())) {
            $userid = $request->user()->getAuthIdentifier();
        } else if (!is_null(Auth::user())) {
            $userid = Auth::user()->id;
        } else {
            return response()->json(['error' => 'Not authorized.'],403);
        }
        $identityEntity = new IdentityEntity();
        $identityEntity->setIdentifier($userid);
        return $identityEntity->getClaims();

        $info = UserIDP::where('user_id',$userid)->with('idp')->first();
        return $info;
    }

}
