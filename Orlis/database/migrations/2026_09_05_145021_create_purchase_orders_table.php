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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_code')->unique();
            $table->foreignId('supplier_id')->constrained('users')->onDelete('cascade'); // Since suppliers are in users table
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('status')->default('pending'); // pending, confirmed, shipped, completed, cancelled
            $table->string('payment_status')->default('unpaid'); // unpaid, paid
            $table->date('expected_delivery_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
