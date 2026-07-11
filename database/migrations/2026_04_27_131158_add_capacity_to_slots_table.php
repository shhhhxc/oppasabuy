<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Changed 'slots' to 'reservation_slots' to match your Model
        Schema::table('reservation_slots', function (Blueprint $table) {
            // Only add these if they don't exist yet
            if (!Schema::hasColumn('reservation_slots', 'status')) {
                $table->string('status')->default('available')->after('dp_amount'); 
            }
            
            // If you really want 'max_capacity' instead of 'max_slots', we add it here:
            if (!Schema::hasColumn('reservation_slots', 'max_capacity')) {
                $table->integer('max_capacity')->default(10)->after('date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservation_slots', function (Blueprint $table) {
            $table->dropColumn(['max_capacity', 'status']);
        });
    }
};