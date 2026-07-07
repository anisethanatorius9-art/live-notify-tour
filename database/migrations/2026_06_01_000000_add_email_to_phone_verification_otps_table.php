<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('phone_verification_otps', function (Blueprint $table) {
            if (! Schema::hasColumn('phone_verification_otps', 'email')) {
                $table->string('email')->nullable()->after('otp');
            }
        });
    }

    public function down(): void
    {
        Schema::table('phone_verification_otps', function (Blueprint $table) {
            if (Schema::hasColumn('phone_verification_otps', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
};
