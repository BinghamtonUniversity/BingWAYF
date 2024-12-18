<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','url','logo','description','auth_type',
        'auth_client_id','public'
    ];

    protected $casts = [
        'public' => 'boolean',
    ];

    public function application_users(){
        return $this->hasMany(UserApplication::class,'application_id');
    }

    public function users() {
        return $this->belongsToMany(User::class,'user_applications','application_id','user_id')->withPivot('id','approved');
    }
}
