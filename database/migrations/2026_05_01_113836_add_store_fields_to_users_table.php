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
        Schema::table('users', function (Blueprint $table) {
         $table->string('intro_video')->nullable(); // For the mandatory intro video
$table->string('badge_level')->default('Basic'); // Basic, Verified, or Trusted
$table->string('brochure_pdf')->nullable(); // For the Canva brochure[cite: 1]
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
