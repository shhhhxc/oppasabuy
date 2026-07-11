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
    Schema::create('reservation_slots', function (Blueprint $table) {
        $table->id();
        $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
        $table->date('date');
        $table->integer('max_slots')->default(20); // Default limit per day
        $table->integer('available_slots'); // Current remaining slots
        $table->decimal('dp_amount', 10, 2)->default(0.00); // Required Downpayment
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_slots');
    }
};
