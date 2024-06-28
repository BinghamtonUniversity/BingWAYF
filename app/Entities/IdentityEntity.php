<?php
namespace App\Entities;

use League\OAuth2\Server\Entities\Traits\EntityTrait;
use OpenIDConnect\Claims\Traits\WithClaims;
use OpenIDConnect\Interfaces\IdentityEntityInterface;
use App\Models\User;

class IdentityEntity implements IdentityEntityInterface
{
    use EntityTrait;
    use WithClaims;

    /**
     * The user to collect the additional information for
     */
    protected User $user;

    /**
     * The identity repository creates this entity and provides the user id
     * @param mixed $identifier
     */
    public function setIdentifier($identifier): void
    {
        $this->identifier = $identifier;
        $this->user = User::where('id',$identifier)->first();
        if (is_null($this->user)) {
            throw new \Exception('User Not Found');
        }
    }

    /**
     * When building the id_token, this entity's claims are collected
     */
    public function getClaims(): array
    {
        return [
            'email' => $this->user->email,
            'given_name' => $this->user->first_name,
            'nickname' => $this->user->first_name,
            'family_name' => $this->user->last_name,
            'name' => $this->user->first_name.' '.$this->user->last_name,
            'sub' => strval($this->user->id),
            'preferred_username' => 'bingwayf-'.$this->user->id,
            'groups' => ['coolguys','wayfusers'],
        ];
    }
}

