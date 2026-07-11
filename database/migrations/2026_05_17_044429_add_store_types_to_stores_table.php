<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
{
    Schema::table('stores', function (Blueprint $table) {
        $table->json('store_type')->nullable()->after('description');
        $table->json('green_market_type')->nullable()->after('store_type');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            //
        });
    }
};
