<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 public function up()
{
    Schema::create('lifestyle_categories', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // e.g., "Hair Salon"
        $table->string('parent_category')->nullable(); // e.g., "Beauty & Personal Care"
        $table->string('icon')->nullable(); // e.g., "bi-scissors"
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lifestyle_categories');
    }
};
