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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('url')->nullable()->default(null);
            $table->string('logo')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->enum('auth_type', ['openid','oauth','cas','saml2']);
            $table->char('auth_client_id')->index(); // "uuid type isn't long enough for owncloud client ids
            $table->boolean('public')->false();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
