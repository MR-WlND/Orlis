<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->nullOnDelete();
            $table->string('name', 100);
            $table->string('email', 150)->unique();
            $table->string('phone', 20)->unique()->nullable();
            $table->string('password', 255);
            $table->string('avatar', 255)->nullable();
            $table->enum('role', ['admin', 'manager', 'staff', 'editor', 'warehouse_staff', 'shipper', 'supplier'])->default('staff');
            $table->boolean('status')->default(1)->comment('1: Hoạt động, 0: Khóa/Chờ xác nhận');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
