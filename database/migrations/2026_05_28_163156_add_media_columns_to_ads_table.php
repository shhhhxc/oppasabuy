<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            // Check only for the column that is actually missing
            if (!Schema::hasColumn('ads', 'video_path')) {
                $table->string('video_path')->nullable()->after('image_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            if (Schema::hasColumn('ads', 'video_path')) {
                $table->dropColumn('video_path');
            }
        });
    }
};
