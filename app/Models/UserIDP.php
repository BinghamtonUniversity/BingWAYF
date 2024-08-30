<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserIDP extends Model
{
    use HasFactory;

    protected $table = 'user_idps';
    protected $fillable = ['idp_id','type','user_id','unique_id','attributes','last_login'];
    protected $casts = [
        'user_id' => 'integer',
        'idp_id' => 'integer',
        'attributes' => 'json',
        'last_login' => 'date:Y-m-d H:i:s'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function saml2_idp(){
        return $this->belongsTo(SAML2IDP::class)->select('idps.id','entityId','name','logo');
    }

}
