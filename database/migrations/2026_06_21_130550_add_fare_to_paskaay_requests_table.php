<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('paskaay_requests', 'fare')) {
            Schema::table('paskaay_requests', function (Blueprint $table) {
                $table->decimal('fare', 10, 2)
                    ->default(0);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('paskaay_requests', 'fare')) {
            Schema::table('paskaay_requests', function (Blueprint $table) {
                $table->dropColumn('fare');
            });
        }
    }
};