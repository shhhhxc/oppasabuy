<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('rooms', function (Blueprint $table) {
        // Adding creator_id as a foreign key
        $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('rooms', function (Blueprint $table) {
        $table->dropForeign(['creator_id']);
        $table->dropColumn('creator_id');
    });
}
};
