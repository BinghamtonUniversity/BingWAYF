<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SAML2IDP extends Model
{
    use HasFactory;

    protected $table = 'saml2_idps';
    protected $fillable = ['entityId','name','singleSignOnServiceUrl','singleLogoutServiceUrl','x509cert','logo','config','enabled','debug','order'];
    protected $casts = ['config' => 'json','enabled' => 'boolean','debug' => 'boolean'];

    public function idp_users(){
        return $this->hasMany(UserIDP::class,'idp_id')->where('type','saml2');
    }

    public function users() {
        return $this->belongsToMany(User::class,'user_idps','idp_id','user_id')->withPivot('id','unique_id','attributes');
    }
}
