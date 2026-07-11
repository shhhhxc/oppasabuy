<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('paskaay_requests')) {
            Schema::create('paskaay_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('rider_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('pickup_address');
                $table->string('destination_address');
                $table->text('note')->nullable();
                $table->string('status')->default('searching_rider');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('paskaay_requests');
    }
};