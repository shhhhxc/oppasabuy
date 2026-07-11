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
    Schema::table('users', function (Blueprint $table) {
        if (!Schema::hasColumn('users', 'role')) {
            $table->string('role')->default('user')->after('email');
        }
        
        if (!Schema::hasColumn('users', 'is_verified')) {
            $table->boolean('is_verified')->default(false)->after('role');
        }

        if (!Schema::hasColumn('users', 'verification_status')) {
            $table->string('verification_status')->default('none')->after('is_verified');
        }
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
