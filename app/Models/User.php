<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['first_name','last_name','email'];
    protected $casts = [];

    public function force_logout(UserApplication $user_application = null) {
        $access_tokens = []; $auth_codes = [];
        if (is_null($user_application)) {
            $access_tokens = DB::table('oauth_access_tokens')->select('id')
                ->where('user_id',$this->id)
                ->get()->pluck('id');
            $auth_codes = DB::table('oauth_auth_codes')->select('id')
                ->where('user_id',$this->id)
                ->get()->pluck('id');
        } else {
            $application = Application::where('id',$user_application->application_id)->first();
            if (!is_null($application) && $application->auth_type === 'openid') {
                $access_tokens = DB::table('oauth_access_tokens')->select('id')
                    ->where('user_id',$this->id)
                    ->where('client_id',$application->auth_client_id)
                    ->get()->pluck('id');
                $auth_codes = DB::table('oauth_auth_codes')->select('id')
                    ->where('user_id',$this->id)
                    ->where('client_id',$application->auth_client_id)
                    ->get()->pluck('id');
            }
        }
        DB::table('oauth_refresh_tokens')->whereIn('access_token_id',$access_tokens)
            ->update(['revoked'=>1]);
        DB::table('oauth_access_tokens')->whereIn('id',$access_tokens)
            ->update(['revoked'=>1]);
        DB::table('oauth_auth_codes')->whereIn('id',$auth_codes)
            ->update(['revoked'=>1]);
    }

    public function user_idps(){
        return $this->hasMany(UserIDP::class,'user_id');
    }

    public function user_applications(){
        return $this->hasMany(UserApplication::class,'user_id');
    }

    public function saml2_idps() {
        return $this->belongsToMany(SAML2IDP::class,'user_idps','user_id','idp_id')->select('idps.id','entityId','name','logo')->withPivot('id','unique_id','attributes');
    }

    public function applications() {
        return $this->belongsToMany(Application::class,'user_applications','user_id','application_id')->withPivot('id','approved');
    }

}
