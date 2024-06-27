<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['first_name','last_name','email'];
    protected $casts = [];

    public function user_idps(){
        return $this->hasMany(UserIDP::class,'user_id');
    }

    public function user_applications(){
        return $this->hasMany(UserApplication::class,'user_id');
    }

    public function idps() {
        return $this->belongsToMany(IDP::class,'user_idps','user_id','idp_id')->select('idps.id','entityId','name','logo')->withPivot('id','unique_id','attributes');
    }

    public function applications() {
        return $this->belongsToMany(Application::class,'user_applications','user_id','application_id')->withPivot('id','approved');
    }

}
