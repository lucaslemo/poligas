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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();

            $table->decimal('vendor_value', $precision = 15, $scale = 2);
            $table->string('status')->default('available');

            $table->unsignedBigInteger('get_product_id');
            $table->foreign('get_product_id')->references('id')->on('products')->onDelete('cascade');

            $table->unsignedBigInteger('get_brand_id');
            $table->foreign('get_brand_id')->references('id')->on('brands')->onDelete('cascade');

            $table->unsignedBigInteger('get_vendor_id');
            $table->foreign('get_vendor_id')->references('id')->on('vendors')->onDelete('cascade');

            $table->unsignedBigInteger('get_sale_id')->nullable();
            $table->foreign('get_sale_id')->references('id')->on('sales')->onDelete('cascade');

            // $table->unique(['get_product_id', 'get_brand_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
