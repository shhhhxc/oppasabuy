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
            // Only add banner_path if it wasn't created during the last crash
            if (!Schema::hasColumn('seller_verifications', 'banner_path')) {
                $table->string('banner_path')->nullable();
            }
            
            // Only add promotional_text if it doesn't exist yet
            if (!Schema::hasColumn('seller_verifications', 'promotional_text')) {
                $table->text('promotional_text')->nullable();
            }
            
            // Only add certificates_data JSON array if it doesn't exist yet
            if (!Schema::hasColumn('seller_verifications', 'certificates_data')) {
                $table->json('certificates_data')->nullable();
            }
            
            // Only add contact_representatives JSON array if it doesn't exist yet
            if (!Schema::hasColumn('seller_verifications', 'contact_representatives')) {
                $table->json('contact_representatives')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seller_verifications', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('seller_verifications', 'banner_path')) $columnsToDrop[] = 'banner_path';
            if (Schema::hasColumn('seller_verifications', 'promotional_text')) $columnsToDrop[] = 'promotional_text';
            if (Schema::hasColumn('seller_verifications', 'certificates_data')) $columnsToDrop[] = 'certificates_data';
            if (Schema::hasColumn('seller_verifications', 'contact_representatives')) $columnsToDrop[] = 'contact_representatives';

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};