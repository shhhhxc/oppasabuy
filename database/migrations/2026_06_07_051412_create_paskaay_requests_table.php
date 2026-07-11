<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
{
    Schema::create('paskaay_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained(); // The buyer
        $table->foreignId('rider_id')->nullable();   // The rider who accepts
        $table->string('pickup_address');
        $table->string('destination_address');
        $table->text('note')->nullable();
        $table->string('status')->default('searching_rider'); // searching_rider, accepted, completed
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paskaay_requests');
    }
};
