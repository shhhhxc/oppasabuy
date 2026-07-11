<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('riders', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('user_id');
            $table->date('birth_date')->nullable()->after('phone');
            $table->text('address')->nullable()->after('birth_date');

            $table->string('emergency_contact_name')->nullable()->after('address');
            $table->string('emergency_contact_number', 20)->nullable()->after('emergency_contact_name');

            $table->string('vehicle_type', 50)->nullable()->after('emergency_contact_number');
            $table->string('vehicle_brand', 100)->nullable()->after('vehicle_type');
            $table->string('vehicle_model', 100)->nullable()->after('vehicle_brand');
            $table->string('vehicle_color', 50)->nullable()->after('vehicle_model');

            $table->date('license_expiration')->nullable()->after('license_number');

            $table->string('orcr_img')->nullable()->after('license_img');
            $table->string('vehicle_photo')->nullable()->after('orcr_img');
            $table->string('selfie_license')->nullable()->after('vehicle_photo');
        });
    }

    public function down(): void
    {
        Schema::table('riders', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'birth_date',
                'address',
                'emergency_contact_name',
                'emergency_contact_number',
                'vehicle_type',
                'vehicle_brand',
                'vehicle_model',
                'vehicle_color',
                'license_expiration',
                'orcr_img',
                'vehicle_photo',
                'selfie_license',
            ]);
        });
    }
};