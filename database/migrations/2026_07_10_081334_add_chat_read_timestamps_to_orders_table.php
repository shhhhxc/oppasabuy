<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (
            Schema::hasTable('orders') &&
            !Schema::hasColumn('orders', 'buyer_last_read_at')
        ) {
            Schema::table('orders', function (Blueprint $table) {
                $table->timestamp('buyer_last_read_at')
                    ->nullable()
                    ->after('paid_at');
            });
        }

        if (
            Schema::hasTable('orders') &&
            !Schema::hasColumn('orders', 'seller_last_read_at')
        ) {
            Schema::table('orders', function (Blueprint $table) {
                $table->timestamp('seller_last_read_at')
                    ->nullable()
                    ->after('buyer_last_read_at');
            });
        }
    }

    public function down(): void
    {
        if (
            Schema::hasTable('orders') &&
            Schema::hasColumn('orders', 'seller_last_read_at')
        ) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('seller_last_read_at');
            });
        }

        if (
            Schema::hasTable('orders') &&
            Schema::hasColumn('orders', 'buyer_last_read_at')
        ) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('buyer_last_read_at');
            });
        }
    }
};