<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Reverting to NOT NULL
            // Note: Ensure there are no NULL values in the database before running this!
            $table->unsignedBigInteger('buyer_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Restore to nullable if rolled back
            $table->unsignedBigInteger('buyer_id')->nullable()->change();
        });
    }
};