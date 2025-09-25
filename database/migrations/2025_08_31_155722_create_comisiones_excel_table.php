<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('comisiones_excel', function (Blueprint $table) {
            $table->id();
            $table->string('archivo')->nullable();      // nombre del archivo subido
            $table->string('hoja')->nullable();         // nombre de la hoja
            $table->string('email')->index();           // email normalizado (lowercase/trim)
            $table->json('row');                        // fila completa como JSON (clave=encabezado)
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comisiones_excel');
    }
};
