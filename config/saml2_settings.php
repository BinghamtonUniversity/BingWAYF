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
MIIDETCCAfkCAQAwbjEXMBUGA1UEAwwOYmluZ2hhbXRvbi5lZHUxCzAJBgNVBAYT
AlVTMREwDwYDVQQIDAhOZXcgWW9yazETMBEGA1UEBwwKQmluZ2hhbXRvbjEeMBwG
A1UECgwVQmluZ2hhbXRvbiBVbml2ZXJzaXR5MIIBIjANBgkqhkiG9w0BAQEFAAOC
AQ8AMIIBCgKCAQEAoQNLoRAv7aNMPVTz8jEgcB4mc4bi4zZeaaLhlPy/w/0h1q3d
MYqi12CTQZ40j/u/Dbo4wn5uD7zn9ZE/RfFqNghpJjS4gCuElDJ7886zmMTtci/H
BeI4XuTLvwtcLNLvYzRNpIfEygHq9ym1XI+IvLfzzyHlwsDgMtu1MNFjhetDFHBQ
TLuQ5rSNcz9TaTKoFs+78w62BY0K/0EaltP15PxnH3oHf5TY8x/GruK4k/IHUlBs
cbexsyQ2mF6dd9Ckkg8Xm4Rdykwl2ERRctSTUsb89Ii0Rn6emBXDnBkU1oMo+HiR
6qNFP4TMVoGxKwMDwvBecVwhEhDah2RP3MYFGQIDAQABoF4wXAYJKoZIhvcNAQkO
MU8wTTAOBgNVHQ8BAf8EBAMCBaAwIAYDVR0lAQH/BBYwFAYIKwYBBQUHAwEGCCsG
AQUFBwMCMBkGA1UdEQQSMBCCDmJpbmdoYW10b24uZWR1MA0GCSqGSIb3DQEBCwUA
A4IBAQB/TpZK2nyvlyBoW24tp2ide+aWdoTIN9y84MWG4IyQCGMsD3xCIf91vT1X
2eCFs5eSbG1abHuj9lXOatxBABjiy+cZsNsU/3ehfTkzrZJm4nj84yjCMQlDFnwf
mFU7V2h5IIMtPGM36mgBPfxw4dArTqqkNlkUS5rqHs87i+TncF8VUT5Fis7GxRIq
oZ02Q8InkrLhJqUKncN94YxvmYC2YZTbXBgp53hEYIcVeMHeGY8s1tj6EYy0yWg2
SALITBB0AOcVZSZh6Y5T0gpgH32Uaz6KGsJeXXw1cXChtbjZS4gbcCQioR/2WxGK
PfL2x/YDvablrxof8iUhkdtyrCt/'),
        'privateKey' => env('SAML2_SP_PRIVATEKEY','
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQChA0uhEC/to0w9
VPPyMSBwHiZzhuLjNl5pouGU/L/D/SHWrd0xiqLXYJNBnjSP+78NujjCfm4PvOf1
kT9F8Wo2CGkmNLiAK4SUMnvzzrOYxO1yL8cF4jhe5Mu/C1ws0u9jNE2kh8TKAer3
KbVcj4i8t/PPIeXCwOAy27Uw0WOF60MUcFBMu5DmtI1zP1NpMqgWz7vzDrYFjQr/
QRqW0/Xk/Gcfegd/lNjzH8au4riT8gdSUGxxt7GzJDaYXp130KSSDxebhF3KTCXY
RFFy1JNSxvz0iLRGfp6YFcOcGRTWgyj4eJHqo0U/hMxWgbErAwPC8F5xXCESENqH
ZE/cxgUZAgMBAAECggEANcZj++bsGvHvBIza7Ih7zPZE4VCrdjcyURWVQa0oXsys
AIcQ2Pr0Z/pZNBEifPQXWv5W9p5DI/5zehCIkVOPpXwpohj5hdUC8nvp6gOe9+bN
ZtWOIujq6sZ/Rc8FefL5AKj3RXwSvWTmlKXZbJCDmoPVadhoZCMGvfWjDirgklj4
8NRB3VWxePlg51Z2PH2+XNDzUgaBgfVdgr3EoJIPKH56Sqf6I9Elklhbk3ne3cmz
ufVw0N1duMAQnkQpMZzzx9mKGk6nz+4FCvowMjOezqmqQ2dQTkWufZlpZyFonKh6
b0SDaBsx7UgXrxu3jX4AQ4UjYPKngXBAHk3JS3J4AQKBgQDLsiDUSEfQdGFzmygZ
YV+Nsztx1yfh43ATvCEDxJRLV7t/StAbW7fNWZmFcQsuaQZ1OdAqqZ+lsFF0m+Br
hyVnSsB3ZuALgHRX/ZfPx844GEtrPAwnnkjfjCIR1s1I6crfsEiNX+IqKlgsOOnC
PWnBCv+Llonn+9MbcN+BBGkdHQKBgQDKW2tWAq326xYqrYZptx+Dn0h/mDsvWxvZ
abrzSQA7FVP18jRYikf5991L9PuhGHsVy6ssABGDhLhzCPmd2WRwf0qsUwrYquXH
8qLU/DJSOCNTEJvB7NF65C/erTdm5Qjbt7tm08DNIrfLojtpNNT7lvEeAvwK0RkM
xARYR/HTLQKBgQCipnL2b10Aw92I/WzQj2NId8TQyzfqdLzq1T8bjh3H558tLqTs
I5mp3JDs7+44ud5RPr/NQTLcj+ULEujpk7m3OAZlkXz3UjQUisdtP6OYQTX/w6wE
+qw/7NrLmdTEEwNPCKl2Ugj7GGLdtPhbhMRsRAb4BFXMKtZQRBoEIGx6tQKBgQC+
UXkELgT8BGKWxRlz9/01x+thxgV0JpZsxtpD0lbbOtX6rU+6LmQ/n3WM6N54xxOM
jj9xZKcUMRFhcAGFCl9CxxWuOXQsD5+JjJTFCGUzmwTuLOKxY1Ap6s9y+SrwJV1N
eOJSdboRtSNz7CNAcaYW8gHYnin66mpK7VjAxvmUCQKBgGsS7oCkJFnydSqGevND
KR++5hGQ2n28eeMboluKDXV8YTgN5ZRwxLhFPzgtVtTYuQIihmiJqOyzxwsjRkxY
4kud+JPaEFCcJGwkvb8EdC8pqS1bW0ENixvvP/Bymr0m1ckfto8D+R4cVq91wqah
wFQkF1lZ66ZXzZn0cgLAD0ux'),

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
        'authnRequestsSigned' => true,

        // Indicates whether the <samlp:logoutRequest> messages sent by this SP
        // will be signed.
        'logoutRequestSigned' => true,

        // Indicates whether the <samlp:logoutResponse> messages sent by this SP
        // will be signed.
        'logoutResponseSigned' => true,

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
