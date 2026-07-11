<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('riders', 'status')) {
            Schema::table('riders', function (Blueprint $table) {
                $table->enum('status', [
                    'pending',
                    'approved',
                    'rejected'
                ])
                    ->default('pending')
                    ->after('rating_count');
            });
        }

        if (!Schema::hasColumn('riders', 'rejection_reason')) {
            Schema::table('riders', function (Blueprint $table) {
                $table->text('rejection_reason')
                    ->nullable()
                    ->after('status');
            });
        }

        if (!Schema::hasColumn('riders', 'verified_at')) {
            Schema::table('riders', function (Blueprint $table) {
                $table->timestamp('verified_at')
                    ->nullable()
                    ->after('rejection_reason');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('riders', 'verified_at')) {
            Schema::table('riders', function (Blueprint $table) {
                $table->dropColumn('verified_at');
            });
        }

        if (Schema::hasColumn('riders', 'rejection_reason')) {
            Schema::table('riders', function (Blueprint $table) {
                $table->dropColumn('rejection_reason');
            });
        }

        if (Schema::hasColumn('riders', 'status')) {
            Schema::table('riders', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};