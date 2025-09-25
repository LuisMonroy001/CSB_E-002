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
        Schema::create('registro_bono', function (Blueprint $table) {
            $table->id();
            $table->string('Linea')->nullable();
            $table->string('No_empleados')->nullable();
            $table->string('Agente')->nullable();
            $table->decimal('Meta_Diaria', 12, 2)->nullable();
            $table->integer('D_Trabajados')->nullable();
            $table->decimal('Meta_Mes', 12, 2)->nullable();
            $table->decimal('Total_Avance', 12, 2)->nullable();
            $table->decimal('Cumplimiento_Meta', 8, 2)->nullable();
            $table->decimal('Productividad', 8, 2)->nullable();
            $table->decimal('Participacion', 8, 2)->nullable();
            $table->decimal('Bono_sin_Acelerador', 12, 2)->nullable();
            $table->decimal('Bono_con_Acelerador', 12, 2)->nullable();
            $table->decimal('Total_Bono', 12, 2)->nullable();
            $table->decimal('Canales_Lineas_Internas', 12, 2)->nullable();
            $table->decimal('Venta_TMK', 12, 2)->nullable();
            $table->decimal('Biometricos', 12, 2)->nullable();
            $table->decimal('Seguros', 12, 2)->nullable();
            $table->decimal('Referido', 12, 2)->nullable();
            $table->decimal('Calidad', 12, 2)->nullable();
            $table->decimal('Ajuste_Periodo_Anterior', 12, 2)->nullable();
            $table->decimal('Descuentos', 12, 2)->nullable();
            $table->decimal('Instructor', 12, 2)->nullable();
            $table->decimal('1QA', 12, 2)->nullable();
            $table->decimal('2QA', 12, 2)->nullable();
            $table->decimal('Total', 12, 2)->nullable();
            $table->string('email')->index(); // 👈 clave para filtrar por usuario
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('registro_bono');
    }
};
