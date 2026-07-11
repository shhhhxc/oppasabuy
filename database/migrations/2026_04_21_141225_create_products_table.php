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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users'); // Links to the seller
            $table->string('name');
            $table->text('description');
            
            // Price is now nullable because Personal Care brochure/inquiry uploads might not have fixed instant prices
            $table->decimal('price', 10, 2)->nullable(); 
            
            $table->integer('stock')->default(0);
            $table->string('category'); // K-Beauty, K-Fashion, etc.
            
            // Tracks where the item belongs: 'oppa_mall', 'own_webstore', 'green_market', 'personal_care'
            $table->string('channel'); 
            
            // Flexible JSON field to capture unique features per module:
            // - green_market: ['harvest_date' => '...', 'unit' => 'kg']
            // - personal_care: ['map_link' => '...', 'brochure_path' => '...']
            $table->json('meta_data')->nullable(); 
            
            $table->string('image_path')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};