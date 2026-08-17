<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('appointment_code')->nullable()->after('id');
            $table->date('appointment_date')->nullable()->after('appointment_datetime');
            $table->string('time_slot')->nullable()->after('appointment_date');
            $table->string('transfer_status')->default('available')->after('status');
            $table->string('cancel_reason')->nullable()->after('note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['appointment_code', 'appointment_date', 'time_slot', 'transfer_status', 'cancel_reason']);
        });
    }
};
