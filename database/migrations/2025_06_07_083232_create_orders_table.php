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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by_user_id')->constrained('users');
            $table->string('status')->default('pending');
            $table->foreignId('driver_id')->nullable()->constrained('drivers'); // Foreign key to drivers table, if applicabl
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('drivers', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
    });
    }
};
