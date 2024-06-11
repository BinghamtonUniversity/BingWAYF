<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class parsemetadata extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:parsemetadata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set('memory_limit','10240M');
        $xml = file_get_contents('/Users/tcortesi/Downloads/metadata.xml');
        libxml_use_internal_errors(true);
        $cleanup_string = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $xml);
        $doc = @simplexml_load_string($cleanup_string);
        if (!$doc) {
            echo "OH NO IT BROKE!";
        }
        $data = json_decode(json_encode($doc),true);

        $entities = [];
        foreach($data['EntityDescriptor'] as $entity) {
            if (isset($entity['IDPSSODescriptor'])) {
                // Initailize Entity
                $newentity = [
                    'name' => null,
                    'entityId' => $entity['@attributes']['entityID'],
                    'singleSignOnService' => ['url' => null],
                    'singleLogoutService' => ['url' => null],
                    'x509cert' => null,
                    'logo' => null
                ];

                // Set Organization 
                if (isset($entity['Organization']['OrganizationDisplayName'])) {
                    if (is_array($entity['Organization']['OrganizationDisplayName'])) {
                        $newentity['name'] = $entity['Organization']['OrganizationDisplayName'][0];
                    } else {
                        $newentity['name'] = $entity['Organization']['OrganizationDisplayName'];
                    }    
                }
                // Set Single-Sign-On URL
                if (isset($entity['IDPSSODescriptor']['SingleSignOnService'])) {
                    foreach($entity['IDPSSODescriptor']['SingleSignOnService'] as $descriptor) {
                        if (isset($descriptor['@attributes']['Binding']) && $descriptor['@attributes']['Binding'] === 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect') {
                            $newentity['singleSignOnService'] = ['url' => $descriptor['@attributes']['Location']];
                        }
                    }
                }
                // Set Single-Logout URL
                if (isset($entity['IDPSSODescriptor']['SingleLogoutService'])) {
                    foreach($entity['IDPSSODescriptor']['SingleLogoutService'] as $descriptor) {
                        if (isset($descriptor['@attributes']['Binding']) && $descriptor['@attributes']['Binding'] === 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect') {
                            $newentity['singleLogoutService'] = ['url' => $descriptor['@attributes']['Location']];
                        }
                    }
                }
                // Set KEY
                $keydescriptor = null;
                if (isset($entity['IDPSSODescriptor']['KeyDescriptor'])) {
                    if (is_array($entity['IDPSSODescriptor']['KeyDescriptor']) && array_is_list($entity['IDPSSODescriptor']['KeyDescriptor'])) {
                        $found = false;
                        foreach($entity['IDPSSODescriptor']['KeyDescriptor'] as $mykeydescriptor) {
                            if (isset($mykeydescriptor['@attributes']) && isset($mykeydescriptor['@attributes']['use']) && 
                                $mykeydescriptor['@attributes']['use'] === 'signing') {
                                $keydescriptor = $mykeydescriptor;
                                $found = true;
                                break;
                            } else if (!isset($mykeydescriptor['@attributes']) || !isset($mykeydescriptor['@attributes']['use'])) {
                                $keydescriptor = $mykeydescriptor;
                                $found = true;
                            }
                        }
                        // if ($found === false) {
                        //     var_dump($entity['IDPSSODescriptor']['KeyDescriptor']); echo "not found!"; exit();
                        // }
                    } else {
                        $keydescriptor = $entity['IDPSSODescriptor']['KeyDescriptor'];
                    }
                } 
                if (isset($keydescriptor['dsKeyInfo']) && 
                    isset($keydescriptor['dsKeyInfo']['dsX509Data']) &&
                    isset($keydescriptor['dsKeyInfo']['dsX509Data']['dsX509Certificate'])) {
                    $newentity['x509cert'] = $keydescriptor['dsKeyInfo']['dsX509Data']['dsX509Certificate'];
                } 
                // Set LOGO
                if (isset($entity['IDPSSODescriptor']['Extensions']['mduiUIInfo']['mduiLogo'])) {
                    if (is_array($entity['IDPSSODescriptor']['Extensions']['mduiUIInfo']['mduiLogo'])) {
                        $newentity['logo'] = $entity['IDPSSODescriptor']['Extensions']['mduiUIInfo']['mduiLogo'][0];
                    } else {
                        $newentity['logo'] = $entity['IDPSSODescriptor']['Extensions']['mduiUIInfo']['mduiLogo'];
                    }
                }
                $entities[] = $newentity;
            }
        }
        echo json_encode($entities,JSON_PRETTY_PRINT);
        exit();
    }
}
