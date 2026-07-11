<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
{
    Schema::table('paskaay_requests', function (Blueprint $table) {
        $table->decimal('latitude', 10, 8)->nullable();
        $table->decimal('longitude', 11, 8)->nullable(); // Dito ang mali, dapat decimal din
    });
}

public function down()
{
    Schema::table('paskaay_requests', function (Blueprint $table) {
        $table->dropColumn(['latitude', 'longitude']);
    });
}
};
