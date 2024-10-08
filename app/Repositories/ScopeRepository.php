<?php

declare(strict_types=1);

namespace App\Repositories;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use OpenIDConnect\Entities\ScopeEntity;

class ScopeRepository implements ScopeRepositoryInterface
{
    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    ) {
        return array_filter($scopes, function (ScopeEntityInterface $scope) {
            return $this->getScopeEntityByIdentifier($scope->getIdentifier());
        });
    }

    public function getScopeEntityByIdentifier($identifier)
    {
        $scopes = [
            'openid' => ['description' => 'Enable OpenID Connect'],
            'profile' => ['description' => 'Information about your profile'],
            'email' => ['description' => 'Information about your email address'],
            'phone' => ['description' => 'Information about your phone numbers'],
            'address' => ['description' => 'Information about your address'],
            'groups' => ['description' => 'View all group memberships'],
            'offline_access' => ['description' => 'Enable Offline Access'],
        ];

        if (array_key_exists($identifier, $scopes) === false) {
            return;
        }

        $scope = new ScopeEntity();
        $scope->setIdentifier($identifier);
        return $scope;
    }
}
