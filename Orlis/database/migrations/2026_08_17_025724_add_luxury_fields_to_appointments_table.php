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
            if (! Schema::hasColumn('appointments', 'appointment_code')) {
                $table->string('appointment_code')->nullable()->after('id');
            }
            if (! Schema::hasColumn('appointments', 'appointment_date')) {
                $table->date('appointment_date')->nullable();
            }
            if (! Schema::hasColumn('appointments', 'time_slot')) {
                $table->string('time_slot')->nullable()->after('status');
            }
            if (! Schema::hasColumn('appointments', 'transfer_status')) {
                $table->string('transfer_status')->default('available')->after('status');
            }
            if (! Schema::hasColumn('appointments', 'cancel_reason')) {
                $table->string('cancel_reason')->nullable()->after('note');
            }
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
