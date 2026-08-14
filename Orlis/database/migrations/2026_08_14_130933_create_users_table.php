<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 150)->unique();
            $table->string('phone', 20)->unique()->nullable();
            $table->string('password', 255);
            $table->string('avatar', 255)->nullable();
            $table->enum('role', ['admin', 'staff', 'customer'])->default('customer');
            $table->enum('membership_level', ['classic', 'silver', 'gold', 'diamond', 'vip'])->default('classic');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};