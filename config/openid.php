<?php

declare(strict_types=1);

return [
    'passport' => [

        /**
         * Place your Passport and OpenID Connect scopes here.
         * To receive an `id_token, you should at least provide the openid scope.
         */
        'tokens_can' => [
            'openid' => 'Enable OpenID Connect',
            'profile' => 'Information about your profile',
            'email' => 'Information about your email address',
            'phone' => 'Information about your phone numbers',
            'address' => 'Information about your address',
            'groups' => 'View all group memberships',
            'offline_access' => 'Enable Offline Access',
        ],
    ],

    /**
     * Place your custom claim sets here.
     */
    'custom_claim_sets' => [
        'groups' => [
            'groups'
        ]
    ],

    /**
     * You can override the repositories below.
     */
    'repositories' => [
        'identity' => \OpenIDConnect\Repositories\IdentityRepository::class,
        'scope' => \App\Repositories\ScopeRepository::class,
    ],

    /**
     * The signer to be used
     * Can be Ecdsa, Hmac or RSA
     */
    'signer' => \Lcobucci\JWT\Signer\Hmac\Sha256::class,

    'routes' => [
        'discovery' => false,
        'jwks' => false,
    ]
];