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
            $table->double('start_cash')->unsigned()->default(1);
            $table->double('end_cash')->unsigned()->nullable();
            $table->date('date')->nullable();
            $table->bigInteger("currency_id")->unsigned();
            $table->timestamps();

            $table->foreign('currency_id')->references('id')->on('currencies');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
