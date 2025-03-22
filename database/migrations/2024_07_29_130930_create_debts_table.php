<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("currency_id")->unsigned();
            $table->bigInteger("supplier_id")->unsigned()->nullable();
            $table->bigInteger("client_id")->unsigned()->nullable();
            $table->double('amount')->unsigned();
            $table->date('date')->nullable();
            $table->text('note')->nullable();
            $table->string('type');
            $table->timestamps();

            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('client_id')->references('id')->on('clients');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
