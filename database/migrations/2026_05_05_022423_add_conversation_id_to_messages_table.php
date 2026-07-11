<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('messages', function (Blueprint $table) {
        // Adding conversation_id to group messages between two users
        $table->string('conversation_id')->nullable()->after('id')->index();
    });
}

public function down()
{
    Schema::table('messages', function (Blueprint $table) {
        $table->dropColumn('conversation_id');
    });
}
};
