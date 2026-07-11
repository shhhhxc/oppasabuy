<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  // xxxx_xx_xx_create_reservations_table.php
public function up(): void
{
    Schema::create('reservations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('slot_id')->constrained('reservation_slots')->onDelete('cascade');
        $table->integer('slot_number'); // e.g., Slot #1, Slot #2
        
        // Status definitions:
        // pending: Nag-request ang buyer pero wala pang DP.
        // reserved: Nag-confirm si seller (50% DP received).
        // paid: Full payment received.
        $table->enum('status', ['pending', 'reserved', 'paid', 'cancelled'])->default('pending');
        
        $table->string('payment_proof')->nullable(); // Image path para sa DP/Full payment receipt
        $table->text('notes')->nullable(); // Optional message from buyer
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
