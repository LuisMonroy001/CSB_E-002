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
        Schema::create('pagos_excels', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('pagos_excel', function (Blueprint $table) {
    $table->id();
    $table->string('linea')->nullable();
    $table->string('no_empleado')->nullable();
    $table->string('agente')->nullable();
    $table->decimal('meta_diaria', 10, 2)->nullable();
    $table->integer('dias_trabajados')->nullable();
    $table->decimal('meta_mes', 10, 2)->nullable();
    $table->decimal('total_avance_mes', 10, 2)->nullable();
    $table->decimal('cumplimiento_meta', 5, 2)->nullable();
    $table->decimal('productividad', 5, 2)->nullable();
    $table->decimal('participacion', 5, 2)->nullable();
    $table->decimal('bono_sin_acelerador', 10, 2)->nullable();
    $table->decimal('bono_con_acelerador', 10, 2)->nullable();
    $table->decimal('total_bono', 10, 2)->nullable();
    $table->decimal('canal', 10, 2)->nullable();
    $table->decimal('tmk', 10, 2)->nullable();
    $table->decimal('biometricos', 10, 2)->nullable();
    $table->decimal('seguros', 10, 2)->nullable();
    $table->decimal('referido', 10, 2)->nullable();
    $table->decimal('afectacion', 10, 2)->nullable();
    $table->decimal('ajuste', 10, 2)->nullable();
    $table->decimal('descuentos', 10, 2)->nullable();
    $table->decimal('instructor', 10, 2)->nullable();
    $table->decimal('uno_qa', 10, 2)->nullable();
    $table->decimal('dos_qa', 10, 2)->nullable();
    $table->decimal('total', 10, 2)->nullable();
    $table->decimal('porcentaje_bolsa', 5, 2)->nullable();
    $table->decimal('bolsa_1qa', 10, 2)->nullable();
    $table->decimal('bolsa_2qa', 10, 2)->nullable();
    $table->decimal('total_variable', 10, 2)->nullable();
    $table->decimal('diferencia_presupuesto', 10, 2)->nullable();
    $table->string('email')->nullable();
    $table->timestamps();
});

    }
};
