<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seller_verifications', function (Blueprint $table) {
            if (!Schema::hasColumn('seller_verifications', 'wet_market_logo')) {
                $table->string('wet_market_logo')->nullable();
            }

            if (!Schema::hasColumn('seller_verifications', 'wet_market_banner_paths')) {
                $table->longText('wet_market_banner_paths')->nullable();
            }

            if (!Schema::hasColumn('seller_verifications', 'sari_sari_logo')) {
                $table->string('sari_sari_logo')->nullable();
            }

            if (!Schema::hasColumn('seller_verifications', 'sari_sari_banner_paths')) {
                $table->longText('sari_sari_banner_paths')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('seller_verifications', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('seller_verifications', 'wet_market_logo')) {
                $columns[] = 'wet_market_logo';
            }

            if (Schema::hasColumn('seller_verifications', 'wet_market_banner_paths')) {
                $columns[] = 'wet_market_banner_paths';
            }

            if (Schema::hasColumn('seller_verifications', 'sari_sari_logo')) {
                $columns[] = 'sari_sari_logo';
            }

            if (Schema::hasColumn('seller_verifications', 'sari_sari_banner_paths')) {
                $columns[] = 'sari_sari_banner_paths';
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};