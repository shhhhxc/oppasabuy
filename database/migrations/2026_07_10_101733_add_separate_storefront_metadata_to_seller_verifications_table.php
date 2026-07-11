<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seller_verifications', function (Blueprint $table) {
            if (!Schema::hasColumn('seller_verifications', 'webstore_promotional_text')) {
                $table->text('webstore_promotional_text')->nullable();
            }

            if (!Schema::hasColumn('seller_verifications', 'webstore_established_year')) {
                $table->unsignedSmallInteger('webstore_established_year')->nullable();
            }

            if (!Schema::hasColumn('seller_verifications', 'webstore_store_hours')) {
                $table->string('webstore_store_hours', 100)->nullable();
            }

            if (!Schema::hasColumn('seller_verifications', 'webstore_contact_representatives')) {
                $table->json('webstore_contact_representatives')->nullable();
            }

            if (!Schema::hasColumn('seller_verifications', 'webstore_certificates_data')) {
                $table->json('webstore_certificates_data')->nullable();
            }

            if (!Schema::hasColumn('seller_verifications', 'wet_market_promotional_text')) {
                $table->text('wet_market_promotional_text')->nullable();
            }

            if (!Schema::hasColumn('seller_verifications', 'wet_market_established_year')) {
                $table->unsignedSmallInteger('wet_market_established_year')->nullable();
            }

            if (!Schema::hasColumn('seller_verifications', 'wet_market_store_hours')) {
                $table->string('wet_market_store_hours', 100)->nullable();
            }

            if (!Schema::hasColumn('seller_verifications', 'wet_market_contact_representatives')) {
                $table->json('wet_market_contact_representatives')->nullable();
            }

            if (!Schema::hasColumn('seller_verifications', 'wet_market_certificates_data')) {
                $table->json('wet_market_certificates_data')->nullable();
            }

            if (!Schema::hasColumn('seller_verifications', 'sari_sari_promotional_text')) {
                $table->text('sari_sari_promotional_text')->nullable();
            }

            if (!Schema::hasColumn('seller_verifications', 'sari_sari_established_year')) {
                $table->unsignedSmallInteger('sari_sari_established_year')->nullable();
            }

            if (!Schema::hasColumn('seller_verifications', 'sari_sari_store_hours')) {
                $table->string('sari_sari_store_hours', 100)->nullable();
            }

            if (!Schema::hasColumn('seller_verifications', 'sari_sari_contact_representatives')) {
                $table->json('sari_sari_contact_representatives')->nullable();
            }

            if (!Schema::hasColumn('seller_verifications', 'sari_sari_certificates_data')) {
                $table->json('sari_sari_certificates_data')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('seller_verifications', function (Blueprint $table) {
            $columns = [
                'webstore_promotional_text',
                'webstore_established_year',
                'webstore_store_hours',
                'webstore_contact_representatives',
                'webstore_certificates_data',

                'wet_market_promotional_text',
                'wet_market_established_year',
                'wet_market_store_hours',
                'wet_market_contact_representatives',
                'wet_market_certificates_data',

                'sari_sari_promotional_text',
                'sari_sari_established_year',
                'sari_sari_store_hours',
                'sari_sari_contact_representatives',
                'sari_sari_certificates_data',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('seller_verifications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};