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
        Schema::create('product_prices', function (Blueprint $table) {
            $table->comment('Campo Tipo Descripción
id integer Identificador único del precio del producto
product_id integer Identificador del producto
currency_id integer Identificador de la divisa
price decimal Precio del producto en la divisa especificada');
            $table->bigIncrements('id');
            $table->timestamps();
            $table->bigInteger('product_id');
            $table->bigInteger('currency_id');
            $table->float('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_prices');
    }
};
