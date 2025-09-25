<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonusCalculation extends Model
{
    protected $fillable = [
        'user_id',
        'quincena',
        'meta_diaria',
        'dias_trabajados',
        'total_avance_mes',
        'descuentos',
        'linea',
        'porcentaje_linea',
        'bolsa_1qa',
        'bolsa_2qa',
        'meta_mensual',
        'cumplimiento',
        'participacion',
        'bono_sin_acel',
        'bono_con_acel',
        'total_bono',
        'uno_qa',
        'dos_qa',
        'total_final',
    ];
}
