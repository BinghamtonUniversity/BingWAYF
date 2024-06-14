<?php

return [
    /**
     * If 'useRoutes' is set to true, the package defines five new routes:
     *
     *    Method | URI                      | Name
     *    -------|--------------------------|------------------
     *    POST   | {routesPrefix}/acs       | saml_acs
     *    GET    | {routesPrefix}/login     | saml_login
     *    GET    | {routesPrefix}/logout    | saml_logout
     *    GET    | {routesPrefix}/metadata  | saml_metadata
     *    GET    | {routesPrefix}/sls       | saml_sls
     */
    'useRoutes' => true,

    'routesPrefix' => '/saml2',

    /**
     * which middleware group to use for the saml routes
     * Laravel 5.2 will need a group which includes StartSession
     */
    'routesMiddleware' => ['saml'],

    /**
     * Indicates how the parameters will be
     * retrieved from the sls request for signature validation
     */
    'retrieveParametersFromServer' => false,

    /**
     * Where to redirect after logout
     */
    'logoutRoute' => '/',

    /**
     * Where to redirect after login if no other option was provided
     */
    'loginRoute' => '/',

    /**
     * Where to redirect after login if no other option was provided
     */
    'errorRoute' => '/',

    /*****
     * One Login Settings
     */

    // If 'strict' is True, then the PHP Toolkit will reject unsigned
    // or unencrypted messages if it expects them signed or encrypted
    // Also will reject the messages if not strictly follow the SAML
    // standard: Destination, NameId, Conditions ... are validated too.
    'strict' => false, //@todo: make this depend on laravel config

    // Enable debug mode (to print errors)
    'debug' => env('APP_DEBUG', true),

    // If 'proxyVars' is True, then the Saml lib will trust proxy headers
    // e.g X-Forwarded-Proto / HTTP_X_FORWARDED_PROTO. This is useful if
    // your application is running behind a load balancer which terminates
    // SSL.
    'proxyVars' => true,

    // Service Provider Data that we are deploying
    'sp' => array(

        // Specifies constraints on the name identifier to be used to
        // represent the requested subject.
        // Take a look on lib/Saml2/Constants.php to see the NameIdFormat supported
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',

        // Usually x509cert and privateKey of the SP are provided by files placed at
        // the certs folder. But we can also provide them with the following parameters
        'x509cert' => env('SAML2_SP_x509','
-----BEGIN CERTIFICATE REQUEST-----
MIIDiDCCAnACAQAwgakxIDAeBgNVBAMMF2Jpbmd3YXlmLmJpbmdoYW10b24uZWR1
MRcwFQYDVQQDDA5iaW5naGFtdG9uLmVkdTEXMBUGA1UEAwwOYmluZ3dheWYubG9j
YWwxCzAJBgNVBAYTAlVTMREwDwYDVQQIDAhOZXcgWW9yazETMBEGA1UEBwwKQmlu
Z2hhbXRvbjEeMBwGA1UECgwVQmluZ2hhbXRvbiBVbml2ZXJzaXR5MIIBIjANBgkq
hkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAqPR5eaZrpOyNtMMh9WTHSib9TGEx6bRs
t+5v+Qh+lAAPm6cFS8M8IbcDeWxDUhKQQPd1+6Bx9zdYCcnxI8T/VbKhxYjWSrp6
dffRNxy78J05SXrv4ZE0jEKOt0LlSFaMcW6aUCjA0aU6+25z9PtB8RXwjFsGC3ou
+0NpRBeyey4S/xFmUA9HGxvxIXkFJ4Qcorf0XBQZTJnJFXIdBCikhVe5GGYESFpF
jLrwLWMuj9qN+ai15JOCDPpBTRZ2V3q1B/BkaV1ODZwa4Y1pndPM/h0vqUUJkUw2
NHnlSzQ9d+BdxbTlIxtzK07p8rZQj00eStgEhiXjIZ4jBYS9wYGZuQIDAQABoIGY
MIGVBgkqhkiG9w0BCQ4xgYcwgYQwDgYDVR0PAQH/BAQDAgWgMCAGA1UdJQEB/wQW
MBQGCCsGAQUFBwMBBggrBgEFBQcDAjAMBgNVHRMBAf8EAjAAMEIGA1UdEQQ7MDmC
DmJpbmd3YXlmLmxvY2Fsgg5iaW5naGFtdG9uLmVkdYIXYmluZ3dheWYuYmluZ2hh
bXRvbi5lZHUwDQYJKoZIhvcNAQELBQADggEBAHCtEqhKqYffl2nOu1Y+aaiBNU7C
GzcfAw5KE2XDsHunyM/XOWpnDbyLj3MNFh/0zu7BLj1dfOdO8CytG5NuQ9qNz7se
SlLwin4jR22DXObpuA3NUqXazX/bRtXUD9TosxfHZdpZDY4iJzeJOzyXoTsiwCbu
DXu+21F5Hom/ZB7jx3Ya8BgyvFiZ36qoZzfdKBHYby3XRRtCx34q9joYD0SP//6o
NG9w0vHqfDFqL62qEPw/ZYmNfLrFBXGELAKBgwBnHjJbWjTUaBv8U2dxGy/KECkt
hJpGfqAKSxM/dp+qSuKECirjDkG86HJvvkcHnTBhfiTTGqZLKSu/Te0dk/U=
-----END CERTIFICATE REQUEST-----
'),
        'privateKey' => env('SAML2_SP_PRIVATEKEY','
-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCo9Hl5pmuk7I20
wyH1ZMdKJv1MYTHptGy37m/5CH6UAA+bpwVLwzwhtwN5bENSEpBA93X7oHH3N1gJ
yfEjxP9VsqHFiNZKunp199E3HLvwnTlJeu/hkTSMQo63QuVIVoxxbppQKMDRpTr7
bnP0+0HxFfCMWwYLei77Q2lEF7J7LhL/EWZQD0cbG/EheQUnhByit/RcFBlMmckV
ch0EKKSFV7kYZgRIWkWMuvAtYy6P2o35qLXkk4IM+kFNFnZXerUH8GRpXU4NnBrh
jWmd08z+HS+pRQmRTDY0eeVLND134F3FtOUjG3MrTunytlCPTR5K2ASGJeMhniMF
hL3BgZm5AgMBAAECggEAJMHbU74DGURlp/f0AWFFgjptTRqwMzoPlQyIy9MvRp+k
xaZWnp8BDAy28sm887QaMigkQPQtFy2xN5/5mGCZdFmCnGkixetADT43BBh6QuTR
u8zFN3vRxNoOFoy1OUGrGFqJs/rJyFJ8PItRKvEm7X5qcP60l/ajFgMaebf2GpoW
+KE7X0RQRUBXc5sluaw5zu78q6V9IuwErLe7pIH+zJootvFwCZlDmt9aP2h3YPlb
mb2rE08UWLwDjIH9Bp9/eI3NQyzeJqoSg535SVrQpH0gx2o9KAXeCApVcFBIVe+D
xfUQG+s/AZaD+r17bZzSBJWV1tIJJIwOfdXwsdiuqQKBgQDXF4IBq3eJi8UCySia
DvqbImXxlgf8dOWfMD4atM3Yhid2VmbwtkzRkQu55tP5YAQ9x8nbfk2eTERYNfY5
33kqjqAptUL1ueY+hvS0cRm3wCvXWxWJHcqtQTnZuckXS00ilkEOilxIms8ODnid
hq0GzXdg/cy5w58KV6li81CuBwKBgQDJFqJRR1dKei2DuulMPBatEyQEhZYr2G/o
GHmPQEd0OQ7E8krK1bgJy7pS6voYwacwgLSSoms97N99IvLRoGUd4dLWB/xM/Omo
QSnpASF3igoZj70C8TUVEkUClDl5cdj5FcxIV0JT4anz7bHmZpizdMQsO8FWEHIr
ERp5cLGKPwKBgQDLClTeEKepvrf99jgOnb8xvJnxIMhvJ0YvLFIj0bNTBuivi8Zm
yh/f4ATquxw0ls/KJhPF0AejO6l0f5Psc6kkZDiKxqHr5k2Tjr/mqbE2RQDdrQy6
P9Y4EI90mS1bgOjSknXdLP0KSKDWSxDO2vfwqPpdI5gjb6dtEu1CDB+H9wKBgFzH
orDZt77XTHTBIq9X8iZ89FtZv4YPYOK3mC6uO8m2Ichg+n1HyphKdXeYjzgo8ZkB
aU9WvSBfUGaOX3a1CLKW5Q53tMNY1f4ZwtqM+QIMoMhWd6EJ5bIJMeoUtcgA7VlP
42dlS2ckcaPogfKKV95E+9Zk47lv8R1J0OtDQcdFAoGAeVpinXRkqnvXvJgZxJU8
VwjaIgmn7OnYidFV5AEN96lYMhmAtwxwRg1mFn8Zc5aJPdKPZVfCNVLP5F7PuPlW
m9tiDcC/BZTEcHVdXyXCULZMfTT15Xb5ejfNBczVveNyRl3MDznnZPpdu7t3UbfY
Srwk3Y/qszZ6IbylWUnZf0c=
-----END PRIVATE KEY-----
'),
        // Identifier (URI) of the SP entity.
        // Leave blank to use the 'saml_metadata' route.
        'entityId' => env('SAML2_SP_ENTITYID',''),

        // Specifies info about where and how the <AuthnResponse> message MUST be
        // returned to the requester, in this case our SP.
        'assertionConsumerService' => array(
            // URL Location where the <Response> from the IdP will be returned,
            // using HTTP-POST binding.
            // Leave blank to use the 'saml_acs' route
            'url' => '',
        ),

        "attributeConsumingService"=> [
            "serviceName" => "BingWAYF",
            "serviceDescription" => 'BingWAYF is a tool which leverages the InCommon Federation for authentication to Binghamton University resources',
            "requestedAttributes" => [
                ["friendlyName" => "givenName", 'name'=> 'urn:oid:2.5.4.42', "isRequired" => true],
                ["friendlyName" => "sn", 'name'=> 'urn:oid:2.5.4.4', "isRequired" => true],
                ["friendlyName" => "mail", 'name'=> 'urn:oid:0.9.2342.19200300.100.1.3', "isRequired" => true],
                ["friendlyName" => "eduPersonTargetedID", 'name'=> 'urn:oid:1.3.6.1.4.1.5923.1.1.1.10', "isRequired" => true],
            ]
        ],

        // Specifies info about where and how the <Logout Response> message MUST be
        // returned to the requester, in this case our SP.
        // Remove this part to not include any URL Location in the metadata.
        'singleLogoutService' => array(
            // URL Location where the <Response> from the IdP will be returned,
            // using HTTP-Redirect binding.
            // Leave blank to use the 'saml_sls' route
            'url' => '',
        ),
    ),




    /***
     *
     *  OneLogin advanced settings
     *
     *
     */
    // Security settings
    'security' => array(

        /** signatures and encryptions offered */

        // Indicates that the nameID of the <samlp:logoutRequest> sent by this SP
        // will be encrypted.
        'nameIdEncrypted' => false,

        // Indicates whether the <samlp:AuthnRequest> messages sent by this SP
        // will be signed.              [The Metadata of the SP will offer this info]
        'authnRequestsSigned' => false,

        // Indicates whether the <samlp:logoutRequest> messages sent by this SP
        // will be signed.
        'logoutRequestSigned' => false,

        // Indicates whether the <samlp:logoutResponse> messages sent by this SP
        // will be signed.
        'logoutResponseSigned' => false,

        /* Sign the Metadata
         False || True (use sp certs) || array (
                                                    keyFileName => 'metadata.key',
                                                    certFileName => 'metadata.crt'
                                                )
        */
        'signMetadata' => false,


        /** signatures and encryptions required **/

        // Indicates a requirement for the <samlp:Response>, <samlp:LogoutRequest> and
        // <samlp:LogoutResponse> elements received by this SP to be signed.
        'wantMessagesSigned' => false,

        // Indicates a requirement for the <saml:Assertion> elements received by
        // this SP to be signed.        [The Metadata of the SP will offer this info]
        'wantAssertionsSigned' => false,

        // Indicates a requirement for the NameID received by
        // this SP to be encrypted.
        'wantNameIdEncrypted' => false,

        'wantNameId' => false,

        // Authentication context.
        // Set to false and no AuthContext will be sent in the AuthNRequest,
        // Set true or don't present thi parameter and you will get an AuthContext 'exact' 'urn:oasis:names:tc:SAML:2.0:ac:classes:PasswordProtectedTransport'
        // Set an array with the possible auth context values: array ('urn:oasis:names:tc:SAML:2.0:ac:classes:Password', 'urn:oasis:names:tc:SAML:2.0:ac:classes:X509'),
        'requestedAuthnContext' => true,
    ),

    // Contact information template, it is recommended to suply a technical and support contacts
    'contactPerson' => array(
        'technical' => array(
            'givenName' => 'Tim Cortesi',
            'emailAddress' => 'tcortesi@binghamton.edu'
        ),
        'administrative' => array(
            'givenName' => 'Tim Cortesi',
            'emailAddress' => 'tcortesi@binghamton.edu'
        ),
        'other' => array(
            'givenName' => 'Binghamton Security Office',
            'emailAddress' => 'security@binghamton.edu'
        ),
        'support' => array(
            'givenName' => 'Binghamton Helpdesk',
            'emailAddress' => 'helpdesk@binghamton.edu'
        ),
    ),

    // Organization information template, the info in en_US lang is recomended, add more if required
    'organization' => [
        'en-US' => [
            'name' => 'Binghamton University',
            'displayname' => 'Binghamton University',
            'url' => 'https://www.binghamton.edu'
        ],
    ],

/* Interoperable SAML 2.0 Web Browser SSO Profile [saml2int]   http://saml2int.org/profile/current

   'authnRequestsSigned' => false,    // SP SHOULD NOT sign the <samlp:AuthnRequest>,
                                      // MUST NOT assume that the IdP validates the sign
   'wantAssertionsSigned' => true,
   'wantAssertionsEncrypted' => true, // MUST be enabled if SSL/HTTPs is disabled
   'wantNameIdEncrypted' => false,
*/
    /* Default IDP -- Overwritten by school IDPs from database during processing */
    'idp' => [
        'name' => 'Default',
        'entityId' => 'https://idp-dev.cc.binghamton.edu/idp/shibboleth',
        'singleSignOnService' => [
            'url' => 'https://idp-dev.cc.binghamton.edu/idp/profile/SAML2/Redirect/SSO',
        ],
        'x509cert' => '
 MIIDTzCCAjegAwIBAgIUIxgMuKdj85wizYHJ1HH1eZfn3IowDQYJKoZIhvcNAQEL 
 BQAwJDEiMCAGA1UEAwwZaWRwLWRldi5jYy5iaW5naGFtdG9uLmVkdTAeFw0xOTA2 
 MTEyMDQ1NDdaFw0zOTA2MTEyMDQ1NDdaMCQxIjAgBgNVBAMMGWlkcC1kZXYuY2Mu 
 YmluZ2hhbXRvbi5lZHUwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCI 
 CsFPx2RqtHWIzT5FV0aNACv+y7K3dZxBlISnRa5a0OHrVL6jr6igvuhjB+4apF5o 
 IJTo/Dr/QoF61MsOxPucY9mhyve/wJ7SHQHgRjyYzxzdFhyq26TodAwLaPBZVEzC 
 NYyPFxwcMwd/ka57tXKy4b2ZeiK6zhLTLkXbvl7pNHjAl6dLSQk+tI80ZW4RSPu7 
 /UhmtzP+UxK9hFIHsEZpt0HFbsLFcdrQs0EBXHVTyzUFqt2s0RVN2oCIupo7pQ0T 
 Ny6qapwafkGq/3bWzsBZWX/zECnC1jWSFusKGk+MlSkSVYGffOnjcV0JiMqg7UHO 
 XZ0+4bC3jzi44cqJU1eDAgMBAAGjeTB3MB0GA1UdDgQWBBRJmhUbP6ZR1XxOOAc2 
 zQuoIh4x9jBWBgNVHREETzBNghlpZHAtZGV2LmNjLmJpbmdoYW10b24uZWR1hjBo 
 dHRwczovL2lkcC1kZXYuY2MuYmluZ2hhbXRvbi5lZHUvaWRwL3NoaWJib2xldGgw 
 DQYJKoZIhvcNAQELBQADggEBAEYSU3NDFFTerdVl9fqN9kJWBBp3gyCP38EuVZgK 
 dqqUsq84rRqp/EgI1PrnjDF8TP6CmY2lgMSqdMk5TDmV66MOctjT8W5MLm8dzX38 
 TSNPD8LMyiYVdMGOxssjsZwwY4udhuLQabGxh2tkhmREdaoi53ToBCZNvbw4l7YW 
 9ZB4u9sGdpg1hHwizPJd1eLyuJvvtWjDtxp3cGwydIHwgzUQ9yd8CVg39MhaeS12 
 t5fgGtDYTFnl9lUIc8+Ecu32QWksNmKOJdvs4pzu/NZ131l+TeTLFN/UmgzFWqC9 
 ad4IZdkOC/S09AD4yeQPFblvQo+tw6Y/drgW8+WxEsC3xR0= ',
    ],
];
