<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('seller_verifications', 'webstore_name')) {
            Schema::table('seller_verifications', function (Blueprint $table) {
                $table->string('webstore_name')
                    ->nullable()
                    ->after('store_name');
            });
        }

        if (!Schema::hasColumn('seller_verifications', 'wet_market_name')) {
            Schema::table('seller_verifications', function (Blueprint $table) {
                $table->string('wet_market_name')
                    ->nullable()
                    ->after('webstore_name');
            });
        }

        if (!Schema::hasColumn('seller_verifications', 'sari_sari_name')) {
            Schema::table('seller_verifications', function (Blueprint $table) {
                $table->string('sari_sari_name')
                    ->nullable()
                    ->after('wet_market_name');
            });
        }
    }

    public function down(): void
    {
        $columns = [];

        if (Schema::hasColumn('seller_verifications', 'webstore_name')) {
            $columns[] = 'webstore_name';
        }

        if (Schema::hasColumn('seller_verifications', 'wet_market_name')) {
            $columns[] = 'wet_market_name';
        }

        if (Schema::hasColumn('seller_verifications', 'sari_sari_name')) {
            $columns[] = 'sari_sari_name';
        }

        if (!empty($columns)) {
            Schema::table('seller_verifications', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
};