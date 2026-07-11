<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seller_verifications', function (Blueprint $table) {
            $table->id();
            // Link to the users table
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Basic Store Information from your "Seller Account" form
            $table->string('store_name')->nullable();
            $table->text('store_description')->nullable();
            
            // Verification Details
            $table->string('id_type'); 
            $table->string('id_number')->nullable();
            $table->string('document_path'); // Path to the Valid ID image
            $table->string('video_path')->nullable(); // For the "Video with ID" required in your UI
            $table->string('logo_path')->nullable();  // For "Logo / Cover / Short Intro Video"
            
            // Choosing the Seller Plan
            // Matches your UI: Free, Basic, Pro, Premium
            $table->string('plan')->default('free'); 
            
            // Admin Control
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable(); 
            
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_verifications');
    }
};