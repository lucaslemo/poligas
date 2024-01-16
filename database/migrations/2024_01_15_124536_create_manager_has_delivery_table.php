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
        Schema::create('manager_has_delivery_men', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('get_manager_user_id');
            $table->foreign('get_manager_user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('get_deliveryman_user_id');
            $table->foreign('get_deliveryman_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manager_has_delivery_men');
    }
};
