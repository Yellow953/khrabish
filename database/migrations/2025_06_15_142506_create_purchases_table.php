<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->integer('number')->unsigned();
            $table->bigInteger("supplier_id")->unsigned();
            $table->bigInteger("currency_id")->unsigned();
            $table->date('purchase_date');
            $table->double('total')->default(0);
            $table->string('invoice_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('currency_id')->references('id')->on('currencies');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
