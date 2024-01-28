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
        Schema::create('sale_has_stocks', function (Blueprint $table) {
            $table->id();

            $table->decimal('sale_value', $precision = 15, $scale = 2);

            $table->unsignedBigInteger('get_sale_id');
            $table->foreign('get_sale_id')->references('id')->on('sales')->onDelete('cascade');

            $table->unsignedBigInteger('get_stock_id')->unique();
            $table->foreign('get_stock_id')->references('id')->on('stocks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_has_stocks');
    }
};
