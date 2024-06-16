<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\IDP;

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

        $this->output->write("<info>Downloading XML metadata ...</info> ",false);
        $xml = file_get_contents('https://mdq.incommon.org/entities');
        $this->info('done!');
        $this->newLine(1);

        $this->info('Parsing metadata ...');

        libxml_use_internal_errors(true);
        $cleanup_string = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $xml);
        $doc = @simplexml_load_string($cleanup_string);
        if (!$doc) {
            echo "OH NO IT BROKE!";
        }
        $data = json_decode(json_encode($doc),true);
 
        $bar = $this->output->createProgressBar(count($data['EntityDescriptor']));
        $bar->start();
        $entities = [];
        foreach($data['EntityDescriptor'] as $entity) {
            $bar->advance();
            if (isset($entity['IDPSSODescriptor'])) {
                // Initailize Entity
                $newentity = [
                    'name' => null,
                    'entityId' => $entity['@attributes']['entityID'],
                    'singleSignOnServiceUrl' => null,
                    'singleLogoutServiceUrl' => null,
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
                            $newentity['singleSignOnServiceUrl'] = $descriptor['@attributes']['Location'];
                        }
                    }
                }
                // Set Single-Logout URL
                if (isset($entity['IDPSSODescriptor']['SingleLogoutService'])) {
                    foreach($entity['IDPSSODescriptor']['SingleLogoutService'] as $descriptor) {
                        if (isset($descriptor['@attributes']['Binding']) && $descriptor['@attributes']['Binding'] === 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect') {
                            $newentity['singleLogoutService'] = $descriptor['@attributes']['Location'];
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
                    $newentity['x509cert'] = trim($keydescriptor['dsKeyInfo']['dsX509Data']['dsX509Certificate']);
                } 
                // Set LOGO
                if (isset($entity['IDPSSODescriptor']['Extensions']['mduiUIInfo']['mduiLogo'])) {
                    if (is_array($entity['IDPSSODescriptor']['Extensions']['mduiUIInfo']['mduiLogo'])) {
                        $newentity['logo'] = $entity['IDPSSODescriptor']['Extensions']['mduiUIInfo']['mduiLogo'][0];
                    } else {
                        $newentity['logo'] = $entity['IDPSSODescriptor']['Extensions']['mduiUIInfo']['mduiLogo'];
                    }
                }

                // Insert / Update Data in Database
                $flight = IDP::updateOrCreate(
                    ['entityId' => $newentity['entityId']],
                    [
                        'name' => $newentity['name'], 
                        'singleSignOnServiceUrl' => $newentity['singleSignOnServiceUrl'],
                        'singleLogoutServiceUrl' => $newentity['singleLogoutServiceUrl'],
                        'x509cert' => $newentity['x509cert'],
                        'logo' => $newentity['logo'],
                    ]
                );                

                $entities[] = $newentity;
            }
        }
        $bar->finish();
        $this->newLine(1);

        $this->info('Complete!');
        // echo json_encode($entities,JSON_PRETTY_PRINT);
        exit();
    }
}
