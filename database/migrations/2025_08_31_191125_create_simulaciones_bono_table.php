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
        Schema::create('simulaciones_bono', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // usuario que simuló
            $table->string('linea')->nullable();
            $table->string('quincena');
            $table->decimal('meta_mensual', 12, 2);
            $table->decimal('total_avance', 12, 2);
            $table->decimal('cumplimiento', 8, 2);
            $table->decimal('participacion', 8, 4);
            $table->decimal('bono', 12, 2);
            $table->decimal('descuentos', 12, 2);
            $table->decimal('total_final', 12, 2);
            $table->timestamps(); // created_at = fecha y hora de simulación
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simulaciones_bono');
    }
};
