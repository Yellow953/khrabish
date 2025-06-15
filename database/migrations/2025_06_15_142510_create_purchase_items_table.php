<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("purchase_id")->unsigned();
            $table->bigInteger("product_id")->unsigned();
            $table->double('quantity')->unsigned()->default(0);
            $table->double('cost')->unsigned()->default(0);
            $table->double('total')->unsigned()->default(0);
            $table->timestamps();

            $table->foreign('purchase_id')->references('id')->on('purchases');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
