<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
{
    Schema::table('products', function (Blueprint $table) {
        // Only add the columns that don't exist yet
        if (!Schema::hasColumn('products', 'discount_value')) {
            $table->decimal('discount_value', 8, 2)->default(0.00);
        }
        if (!Schema::hasColumn('products', 'discount_type')) {
            $table->string('discount_type')->default('percent');
        }
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn(['on_sale', 'discount_value', 'discount_type']);
    });
}
};
