<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IDP;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $idp = new IDP([
            'entityId' => config('saml2_settings.idp.entityId'),
            'name' => 'Binghamton IDP (DEV)',
            'singleSignOnServiceUrl' => config('saml2_settings.idp.singleSignOnService.url'),
            'singleLogoutServiceUrl' => null,
            'x509cert' => config('saml2_settings.idp.x509cert'),
            'enabled' => true,
            'debug' => false,
        ]);
        $idp->save();
    }
}