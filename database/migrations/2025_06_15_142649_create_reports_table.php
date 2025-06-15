<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id")->unsigned();
            $table->datetime('start_datetime');
            $table->datetime('end_datetime');
            $table->double('total_sales')->unsigned()->default(0);
            $table->double('total_tax')->unsigned()->default(0);
            $table->double('total_discounts')->unsigned()->default(0);
            $table->double('cash_amount')->unsigned()->default(0);
            $table->double('transaction_count')->unsigned()->default(0);
            $table->bigInteger("currency_id")->unsigned();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('currency_id')->references('id')->on('currencies');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
