<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserApplication extends Model
{
    use HasFactory;

    protected $table = 'user_applications';
    protected $fillable = ['application_id','user_id','approved','admin','last_login'];
    protected $casts = [
        'user_id' => 'integer',
        'application_id' => 'integer',
        'approved' => 'boolean',
        'admin' => 'boolean',
        'last_login' => 'date:Y-m-d H:i:s'
    ];
    protected $with = ['user','application'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function application(){
        return $this->belongsTo(Application::class);
    }
}
