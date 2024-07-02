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
        Schema::create('user_idps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('idp_id')->nullable()->index();
            $table->string('unique_id')->index();
            $table->json('attributes')->nullable()->default(null);
            $table->timestamp('last_login')->nullable()->default(null);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('idp_id')->references('id')->on('idps');
            $table->unique('user_id', 'idp_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_idps');
    }
};
