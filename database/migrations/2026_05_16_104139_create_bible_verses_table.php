<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('bible_verses', function (Blueprint $table) {
        $table->id();
        $table->string('display_type'); // 'text' or 'image'
        $table->text('verse_text')->nullable();
        $table->string('reference')->nullable();
        $table->string('image_path')->nullable();
        $table->boolean('is_published')->default(true);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bible_verses');
    }
};
