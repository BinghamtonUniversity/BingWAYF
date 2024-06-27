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
            $table->string('logo')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->enum('auth_type', ['openid','oauth','cas','saml2']);
            $table->unsignedBigInteger('auth_client_id')->index();
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
