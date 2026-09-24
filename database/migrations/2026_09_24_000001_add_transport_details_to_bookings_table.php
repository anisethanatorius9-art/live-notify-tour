<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            $table->foreignId('service_id')->nullable()->change();
            $table->string('booking_type')->default('service')->after('service_id');
            $table->string('transport_type')->nullable();
            $table->string('origin')->nullable();
            $table->string('destination')->nullable();
            $table->decimal('origin_latitude', 10, 7)->nullable();
            $table->decimal('origin_longitude', 10, 7)->nullable();
            $table->decimal('destination_latitude', 10, 7)->nullable();
            $table->decimal('destination_longitude', 10, 7)->nullable();
            $table->decimal('distance_km', 10, 2)->nullable();
            $table->unsignedInteger('duration_minutes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            $table->dropColumn([
                'booking_type',
                'transport_type',
                'origin',
                'destination',
                'origin_latitude',
                'origin_longitude',
                'destination_latitude',
                'destination_longitude',
                'distance_km',
                'duration_minutes',
            ]);
            $table->foreignId('service_id')->nullable(false)->change();
        });
    }
};
