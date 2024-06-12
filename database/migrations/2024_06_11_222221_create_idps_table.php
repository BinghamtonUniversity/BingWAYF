<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('idps', function (Blueprint $table) {
            $table->id();
            $table->string('entityId')->unique();
            $table->string('name');
            $table->string('singleSignOnServiceUrl')->nullable()->default(null);
            $table->string('singleLogoutServiceUrl')->nullable()->default(null);
            $table->text('x509cert')->nullable()->default(null);
            $table->mediumtext('logo')->nullable()->default(null);
            $table->string('config')->json()->default('');
            $table->string('enabled')->boolean()->default(false);
            $table->string('debug')->boolean()->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('idps');
    }
};
