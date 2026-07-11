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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('buyer_id')->constrained('users');
        $table->foreignId('seller_id')->constrained('users');
        $table->decimal('total_price', 10, 2);
        
        // Flow: pending -> awaiting_video -> video_uploaded -> video_approved -> paid
        $table->string('status')->default('pending'); 
        
        // --- Required Columns ---
        $table->string('payment_method')->nullable(); 
        $table->text('rejection_reason')->nullable(); // In case the buyer rejects the video
        // -------------------------

        $table->string('video_proof_path')->nullable(); 
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};