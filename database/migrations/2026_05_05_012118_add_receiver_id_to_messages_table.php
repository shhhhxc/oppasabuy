<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $blade) {
            // Adding the receiver_id column to track who the message is for
            $blade->unsignedBigInteger('receiver_id')->after('user_id')->nullable();
            
            // Optional: Add a foreign key if you want strict data integrity
            $blade->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $blade) {
            $blade->dropForeign(['receiver_id']);
            $blade->dropColumn('receiver_id');
        });
    }
};