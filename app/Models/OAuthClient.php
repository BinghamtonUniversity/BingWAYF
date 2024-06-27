<?php
 
namespace App\Models;
 
use Laravel\Passport\Client as BaseClient;
 
class OAuthClient extends BaseClient
{
    /**
     * Determine if the client should skip the authorization prompt.
     */
    public function skipsAuthorization(): bool
    {
        return true; //$this->firstParty();
    }
}

