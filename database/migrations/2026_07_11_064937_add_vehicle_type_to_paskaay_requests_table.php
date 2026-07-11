<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('paskaay_requests', function (Blueprint $table) {
        $table->string('vehicle_type')
              ->default('motorcycle')
              ->after('fare');
    });
}

public function down()
{
    Schema::table('paskaay_requests', function (Blueprint $table) {
        $table->dropColumn('vehicle_type');
    });
}
};
