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
        Schema::table('orders', function (Blueprint $table) {
            // Add rider_id to link the order to a rider
            if (!Schema::hasColumn('orders', 'rider_id')) {
                $table->unsignedBigInteger('rider_id')->nullable()->after('status');
            }

            // Add rider_price for the specific rider's earnings
            if (!Schema::hasColumn('orders', 'rider_price')) {
                $table->decimal('rider_price', 10, 2)->default(0.00)->after('rider_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['rider_id', 'rider_price']);
        });
    }
};