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
            if (!Schema::hasColumn('seller_verifications', 'banner_slider_paths')) {
                $table->text('banner_slider_paths')->nullable()->after('banner_path');
            }
            if (!Schema::hasColumn('seller_verifications', 'contact_representatives')) {
                $table->text('contact_representatives')->nullable()->after('banner_slider_paths');
            }
            if (!Schema::hasColumn('seller_verifications', 'certificates_data')) {
                $table->text('certificates_data')->nullable()->after('contact_representatives');
            }
            if (!Schema::hasColumn('seller_verifications', 'video_intro_path')) {
                $table->string('video_intro_path')->nullable()->after('certificates_data');
            }
            if (!Schema::hasColumn('seller_verifications', 'established_year')) {
                $table->year('established_year')->nullable()->after('video_intro_path');
            }
            if (!Schema::hasColumn('seller_verifications', 'store_hours')) {
                $table->string('store_hours')->nullable()->after('established_year');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seller_verifications', function (Blueprint $table) {
            $columns = [
                'banner_slider_paths',
                'contact_representatives',
                'certificates_data',
                'video_intro_path',
                'established_year',
                'store_hours'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('seller_verifications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};