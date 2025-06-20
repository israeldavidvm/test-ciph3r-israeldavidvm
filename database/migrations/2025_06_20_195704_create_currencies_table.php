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
        Schema::create('currencies', function (Blueprint $table) {
            $table->comment('Campo Tipo Descripción
id integer Identificador único de la divisa
name string Nombre de la divisa
symbol string Símbolo de la divisa
exchange_rate decimal Tasa de cambio de la divisa');
            $table->bigIncrements('id');
            $table->text('name');
            $table->text('symbol');
            $table->decimal('exchange_rate', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
