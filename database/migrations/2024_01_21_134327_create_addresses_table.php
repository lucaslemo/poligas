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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('street');
            $table->string('number');
            $table->string('complement')->nullable();
            $table->string('neighborhood');
            $table->string('city');
            $table->string('state');
            $table->string('zip_code');
            $table->boolean('primary')->default(false);

            $table->unsignedBigInteger('get_customer_id')->nullable();
            $table->foreign('get_customer_id')->references('id')->on('customers')->onDelete('cascade');

            $table->unsignedBigInteger('get_vendor_id')->nullable();
            $table->foreign('get_vendor_id')->references('id')->on('vendors')->onDelete('cascade');

            $table->check('NOT (get_customer_id IS NOT NULL AND get_vendor_id IS NOT NULL)', 'address_must_not_have_customer_and_vendor');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};