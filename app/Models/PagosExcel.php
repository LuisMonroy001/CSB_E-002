<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagosExcel extends Model
{
    protected $table = 'pagos_excel'; // <- Aquí indicamos la tabla personalizada
    public $timestamps = true; // O false si no usas created_at / updated_at

    protected $fillable = [
        'linea',
        'no_empleado',
        'agente',
        'meta_diaria',
        'dias_trabajados',
        'meta_mes',
        'total_avance_mes',
        'cumplimiento_meta',
        'productividad',
        'participacion',
        'bono_sin_acelerador',
        'bono_con_acelerador',
        'total_bono',
        'canal_lineas_internas',
        'venta_tmk',
        'biometricos',
        'seguros',
        'referido',
        'afectacion_calidad_o_incidencias',
        'ajuste_periodo_anterior',
        'descuentos',
        'instructor',
        'uno_qa',
        'dos_qa',
        'total',
        'porcentaje_bolsa',
        'bolsa_uno_qa',
        'bolsa_dos_qa',
        'total_variable',
        'diferencia_presupuesto',
        'email',
    ];
}


