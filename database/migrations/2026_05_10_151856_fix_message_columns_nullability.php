<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('messages', function (Blueprint $table) {
        // Allow order_id to be empty (for Group Chats)
        $table->unsignedBigInteger('order_id')->nullable()->change();
        
        // Ensure room_id is also nullable (for Order Chats)
        // Note: We use unsignedBigInteger here to match the ID type
        $table->unsignedBigInteger('room_id')->nullable()->change();
    });
}

public function down(): void
{
    Schema::table('messages', function (Blueprint $table) {
        $table->unsignedBigInteger('order_id')->nullable(false)->change();
        $table->unsignedBigInteger('room_id')->nullable(false)->change();
    });
}
};
