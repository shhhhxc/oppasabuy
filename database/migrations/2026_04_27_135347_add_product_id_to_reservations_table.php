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
    Schema::table('reservations', function (Blueprint $table) {
        // We add the column after slot_id and set up the foreign key relationship
        $table->unsignedBigInteger('product_id')->after('slot_id');
        
        // Optional: Add a foreign key constraint to maintain data integrity
        $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('reservations', function (Blueprint $table) {
        $table->dropForeign(['product_id']);
        $table->dropColumn('product_id');
    });
}
};
