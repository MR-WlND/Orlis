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
        Schema::table('banners', function (Blueprint $table) {
            $table->string('image_mobile_path')->nullable()->after('image_path');
            $table->json('category_ids')->nullable()->after('position');
            $table->boolean('is_global')->default(false)->after('category_ids');
            $table->dateTime('start_time')->nullable()->after('is_active');
            $table->dateTime('end_time')->nullable()->after('start_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn([
                'image_mobile_path',
                'category_ids',
                'is_global',
                'start_time',
                'end_time',
            ]);
        });
    }
};
