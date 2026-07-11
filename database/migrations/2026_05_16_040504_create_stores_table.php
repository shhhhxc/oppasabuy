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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('logo')->nullable();
            $table->text('address')->nullable();
            $table->string('business_email');
            $table->string('phone_number');
            $table->text('description');
            $table->json('store_types'); // This holds the array of selected stores
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function decline(): void
    {
        Schema::dropIfExists('stores');
    }
};