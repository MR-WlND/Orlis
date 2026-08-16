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
        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('color', 50)->virtualAs("JSON_UNQUOTE(JSON_EXTRACT(attributes, '$.color'))")->index();
            $table->string('size', 50)->virtualAs("JSON_UNQUOTE(JSON_EXTRACT(attributes, '$.size'))")->index();
            $table->string('material', 100)->virtualAs("JSON_UNQUOTE(JSON_EXTRACT(attributes, '$.material'))")->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropIndex(['color']);
            $table->dropIndex(['size']);
            $table->dropIndex(['material']);
            $table->dropColumn(['color', 'size', 'material']);
        });
    }
};
