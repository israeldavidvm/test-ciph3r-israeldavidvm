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
        Schema::create('products', function (Blueprint $table) {
            $table->comment('Campo Tipo Description
id integer Identificador único del producto
name string Nombre del producto
description string Descripción del producto
price decimal Precio del producto en la divisa base
currency_id integer Identificador de la divisa base
tax_cost decimal Costo de impuestos del producto
manufacturing_cost decimal Costo de fabricación del producto');
            $table->bigIncrements('id');
            $table->timestamps();
            $table->text('name');
            $table->text('description')->nullable();
            $table->float('price')->nullable();
            $table->float('tax_cost')->nullable();
            $table->float('manufacturing_cost')->nullable();
            $table->bigInteger('currency_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
