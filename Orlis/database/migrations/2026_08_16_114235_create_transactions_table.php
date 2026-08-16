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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->restrictOnDelete();
            $table->enum('type', ['payment', 'refund', 'deposit'])->default('payment');
            $table->decimal('amount', 15, 2);
            $table->string('currency', 10)->default('VND');
            $table->decimal('exchange_rate', 15, 6)->default(1);
            $table->enum('payment_method', ['cod', 'bank_transfer', 'momo', 'vnpay', 'credit_card']);
            $table->string('transaction_code', 100)->nullable();
            $table->json('gateway_response')->nullable();
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
