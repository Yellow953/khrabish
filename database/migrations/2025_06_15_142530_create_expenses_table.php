<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->integer('number')->unsigned();
            $table->date('date');
            $table->string('category');
            $table->string('description')->nullable();
            $table->double('amount')->unsigned()->default(0);
            $table->bigInteger("currency_id")->unsigned();
            $table->timestamps();

            $table->foreign('currency_id')->references('id')->on('currencies');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
