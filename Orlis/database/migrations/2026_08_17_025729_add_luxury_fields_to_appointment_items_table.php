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
        Schema::table('appointment_items', function (Blueprint $table) {
            $table->foreignId('source_store_id')->nullable()->constrained('stores')->nullOnDelete()->after('variant_id');
            $table->boolean('needs_transfer')->default(false)->after('source_store_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointment_items', function (Blueprint $table) {
            $table->dropForeign(['source_store_id']);
            $table->dropColumn(['source_store_id', 'needs_transfer']);
        });
    }
};
