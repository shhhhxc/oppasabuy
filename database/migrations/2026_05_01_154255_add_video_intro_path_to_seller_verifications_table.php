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
    Schema::table('seller_verifications', function (Blueprint $table) {
        // Adding the column after an existing column (e.g., status)
        $table->string('video_intro_path')->nullable()->after('status');
    });
}

public function down(): void
{
    Schema::table('seller_verifications', function (Blueprint $table) {
        $table->dropColumn('video_intro_path');
    });
}
};
