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

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function user_idps(){
        return $this->hasMany(UserIDP::class,'user_id');
    }

    public function idps() {
        return $this->belongsToMany(IDP::class,'user_idps','user_id','idp_id')->select('idps.id','entityId','name','logo')->withPivot('id','unique_id','attributes');
    }

}
