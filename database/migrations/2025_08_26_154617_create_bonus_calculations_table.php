<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bonus_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // entradas
            $table->string('quincena'); // 1QA | 2QA
            $table->decimal('meta_diaria', 12, 2);
            $table->unsignedInteger('dias_trabajados');
            $table->decimal('total_avance_mes', 14, 2)->default(0);
            $table->decimal('descuentos', 14, 2)->default(0);

            // datos de línea/bolsa usados
            $table->string('linea')->nullable();
            $table->decimal('porcentaje_linea', 8, 4)->nullable(); // ej 0.0670
            $table->decimal('bolsa_1qa', 14, 2)->nullable();
            $table->decimal('bolsa_2qa', 14, 2)->nullable();

            // cálculos
            $table->decimal('meta_mensual', 14, 2)->default(0);
            $table->decimal('cumplimiento', 8, 2)->default(0);     // %
            $table->decimal('participacion', 10, 6)->default(0);   // factor (no %)
            $table->decimal('bono_sin_acel', 14, 2)->default(0);
            $table->decimal('bono_con_acel', 14, 2)->default(0);
            $table->decimal('total_bono', 14, 2)->default(0);
            $table->decimal('uno_qa', 14, 2)->default(0);
            $table->decimal('dos_qa', 14, 2)->default(0);
            $table->decimal('total_final', 14, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bonus_calculations');
    }
};
