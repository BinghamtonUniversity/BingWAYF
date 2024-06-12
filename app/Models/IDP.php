<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IDP extends Model
{
    use HasFactory;

    protected $table = 'idps';
    protected $fillable = ['entityId','name','singleSignOnServiceUrl','singleLogoutServiceUrl','x509cert','logo','config','enabled'];

    protected function casts(): array {
        return [
            'config' => 'json',
            'enabled' => 'boolean',
        ];
    }

}
