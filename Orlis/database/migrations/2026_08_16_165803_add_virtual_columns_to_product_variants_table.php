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
        if (! Schema::hasColumn('product_variants', 'color')) {
            Schema::table('product_variants', function (Blueprint $table) {
                $table->string('color', 50)->virtualAs("JSON_UNQUOTE(JSON_EXTRACT(attributes, '$.color'))")->nullable();
                $table->string('size', 20)->virtualAs("JSON_UNQUOTE(JSON_EXTRACT(attributes, '$.size'))")->nullable()->after('color');
                $table->string('material', 100)->virtualAs("JSON_UNQUOTE(JSON_EXTRACT(attributes, '$.material'))")->nullable()->after('size');

                $table->index('color');
                $table->index('size');
                $table->index('material');
            });
        }
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
