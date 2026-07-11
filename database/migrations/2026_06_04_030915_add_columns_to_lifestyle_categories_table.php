<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('lifestyle_categories', 'name')) {
            Schema::table('lifestyle_categories', function (Blueprint $table) {
                $table->string('name')
                    ->after('id');
            });
        }

        if (!Schema::hasColumn('lifestyle_categories', 'parent_category')) {
            Schema::table('lifestyle_categories', function (Blueprint $table) {
                $table->string('parent_category')
                    ->nullable()
                    ->after('name');
            });
        }

        if (!Schema::hasColumn('lifestyle_categories', 'slug')) {
            Schema::table('lifestyle_categories', function (Blueprint $table) {
                $table->string('slug')
                    ->nullable()
                    ->after('parent_category');
            });
        }
    }

    public function down(): void
    {
        $columns = [];

        if (Schema::hasColumn('lifestyle_categories', 'slug')) {
            $columns[] = 'slug';
        }

        if (Schema::hasColumn('lifestyle_categories', 'parent_category')) {
            $columns[] = 'parent_category';
        }

        if (Schema::hasColumn('lifestyle_categories', 'name')) {
            $columns[] = 'name';
        }

        if (!empty($columns)) {
            Schema::table('lifestyle_categories', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
};