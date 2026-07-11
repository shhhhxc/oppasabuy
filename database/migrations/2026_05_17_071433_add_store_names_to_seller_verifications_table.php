<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('seller_verifications', function (Blueprint $table) {
        // Idagdag ang tatlong magkakaibang pangalan
        $table->string('webstore_name')->nullable()->after('store_name');
        $table->string('wet_market_name')->nullable()->after('webstore_name');
        $table->string('sari_sari_name')->nullable()->after('wet_market_name');
    });
}

public function down(): void
{
    Schema::table('seller_verifications', function (Blueprint $table) {
        $table->dropColumn(['webstore_name', 'wet_market_name', 'sari_sari_name']);
    });
}
};
