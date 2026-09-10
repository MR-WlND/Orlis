<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('store_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('variant_id')->constrained('product_variants')->cascadeOnDelete();
            $table->unsignedInteger('stock_qty')->default(0);    // >= 0 enforced by unsignedInteger
            $table->unsignedInteger('reserved_qty')->default(0);
            $table->timestamps();

            $table->unique(['store_id', 'variant_id']);
        });

        // Thêm CHECK constraint tương thích cả MySQL lẫn SQLite
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE store_inventory ADD CONSTRAINT check_stock_qty CHECK (stock_qty >= 0)');
            DB::statement('ALTER TABLE store_inventory ADD CONSTRAINT check_reserved_qty CHECK (reserved_qty <= stock_qty)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_inventory');
    }
};
