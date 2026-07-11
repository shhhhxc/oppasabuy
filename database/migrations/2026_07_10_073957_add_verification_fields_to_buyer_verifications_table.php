<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('buyer_verifications', 'rejection_reason')) {
            Schema::table('buyer_verifications', function (Blueprint $table) {
                $table->text('rejection_reason')
                    ->nullable()
                    ->after('status');
            });
        }

        if (!Schema::hasColumn('buyer_verifications', 'verified_at')) {
            Schema::table('buyer_verifications', function (Blueprint $table) {
                $table->timestamp('verified_at')
                    ->nullable()
                    ->after('rejection_reason');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('buyer_verifications', 'verified_at')) {
            Schema::table('buyer_verifications', function (Blueprint $table) {
                $table->dropColumn('verified_at');
            });
        }

        if (Schema::hasColumn('buyer_verifications', 'rejection_reason')) {
            Schema::table('buyer_verifications', function (Blueprint $table) {
                $table->dropColumn('rejection_reason');
            });
        }
    }
};