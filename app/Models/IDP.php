<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IDP extends Model
{
    use HasFactory;

    protected $table = 'idps';
    protected $fillable = ['entityId','name','singleSignOnServiceUrl','singleLogoutServiceUrl','x509cert','logo','config','enabled','debug','order'];
    protected $casts = ['config' => 'json','enabled' => 'boolean','debug' => 'boolean'];

    public function idp_users(){
        return $this->hasMany(UserIDP::class,'idp_id');
    }

    public function users() {
        return $this->belongsToMany(User::class,'user_idps','idp_id','user_id')->withPivot('id','unique_id','attributes');
    }
}
