<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->string('product_name')->nullable();
        $table->string('product_type')->nullable();
        $table->integer('quantity')->nullable();
        $table->text('address')->nullable();
        $table->boolean('is_pasabuy_request')->default(false);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
