<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add rider_id if it doesn't exist
            if (!Schema::hasColumn('orders', 'rider_id')) {
                $table->unsignedBigInteger('rider_id')->nullable()->after('id');
            }
            
            // Add price if it doesn't exist
            if (!Schema::hasColumn('orders', 'price')) {
                $table->decimal('price', 10, 2)->default(0.00)->after('status');
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['rider_id', 'price']);
        });
    }
};