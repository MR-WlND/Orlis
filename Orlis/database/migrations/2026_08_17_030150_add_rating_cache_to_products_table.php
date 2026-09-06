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
        if (! Schema::hasColumn('products', 'rating_avg')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('rating_avg', 3, 1)->default(0)->after('is_active');
                $table->integer('reviews_count')->default(0)->after('rating_avg');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['rating_avg', 'reviews_count']);
        });
    }
};
