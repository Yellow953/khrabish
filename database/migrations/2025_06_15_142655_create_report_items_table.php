<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("report_id")->unsigned();
            $table->bigInteger("product_id")->unsigned();
            $table->double('quantity_sold')->unsigned()->default(0);
            $table->double('total_amount')->unsigned()->default(0);
            $table->timestamps();

            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_items');
    }
};
