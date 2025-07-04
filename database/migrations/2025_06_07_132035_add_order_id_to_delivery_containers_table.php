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
       Schema::table('delivery_containers', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->after('container_id');

            // If you want, add foreign key constraint:
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       
        Schema::table('delivery_containers', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn('order_id');
        });
    }
};
