<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Add this

return new class extends Migration
{
    public function up()
    {
        // 1. Fill NULL buyer_id values first so the 'NOT NULL' constraint doesn't fail.
        // We set the buyer_id to match the seller_id for existing Group Chats.
        DB::table('orders')->whereNull('buyer_id')->update([
            'buyer_id' => DB::raw('seller_id')
        ]);

        // 2. Now change the column to NOT NULL
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('buyer_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('buyer_id')->nullable()->change();
        });
    }
};