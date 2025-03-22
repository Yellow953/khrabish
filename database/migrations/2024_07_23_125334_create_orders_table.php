<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("cashier_id")->unsigned()->nullable();
            $table->bigInteger("client_id")->unsigned()->nullable();
            $table->bigInteger("currency_id")->unsigned();
            $table->string('order_number');
            $table->double('sub_total')->unsigned()->default(0);
            $table->double('tax')->unsigned()->default(0);
            $table->double('discount')->unsigned()->default(0);
            $table->double('total')->unsigned()->default(0);
            $table->unsignedInteger('products_count');
            $table->string('payment_method')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('cashier_id')->references('id')->on('users');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('currency_id')->references('id')->on('currencies');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
