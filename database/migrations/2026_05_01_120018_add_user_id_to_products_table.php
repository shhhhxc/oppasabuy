<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Handle Column Creation
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'user_id')) {
                // We make it nullable first to prevent crashes with existing data
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
        });

        // 2. Data Integrity Fix
        // Assign any products that don't have a user_id to the first user in your DB.
        // This prevents the "Integrity constraint violation" error.
        $firstUser = DB::table('users')->first();
        if ($firstUser) {
            DB::table('products')
                ->whereNull('user_id')
                ->orWhere('user_id', 0)
                ->update(['user_id' => $firstUser->id]);
        }

        // 3. Add Foreign Key Constraint
        Schema::table('products', function (Blueprint $table) {
            // Check if foreign key already exists to prevent duplicate errors
            // This is a standard naming convention for Laravel foreign keys
            $foreignKeyName = 'products_user_id_foreign';
            
            // We use a try-catch or raw check to ensure we don't double-add the constraint
            try {
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
            } catch (\Exception $e) {
                // If it already exists, just ignore and move on
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};