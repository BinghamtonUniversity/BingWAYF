<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class Group extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name','slug','description'];
    protected $casts = [];

    public function members(){
        return $this->hasMany(UserGroup::class,'user_id');
    }

    public function users() {
        return $this->belongsToMany(User::class,'group_members','user_id','group_id');
    }

}
