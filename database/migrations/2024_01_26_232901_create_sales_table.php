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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            $table->decimal('total_value', $precision = 15, $scale = 2);
            $table->dateTime('payment_date')->nullable();

            $table->unsignedBigInteger('get_customer_id');
            $table->foreign('get_customer_id')->references('id')->on('customers')->onDelete('cascade');

            $table->unsignedBigInteger('get_user_id');
            $table->foreign('get_user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('get_deliveryman_user_id')->nullable();
            $table->foreign('get_deliveryman_user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('get_payment_type_id')->nullable();
            $table->foreign('get_payment_type_id')->references('id')->on('payment_types')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
