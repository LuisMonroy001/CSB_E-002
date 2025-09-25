<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimulacionBono extends Model
{
    protected $table = 'simulaciones_bono';
    protected $fillable = [
        'user_id','linea','quincena','meta_mensual',
        'total_avance','cumplimiento','participacion',
        'bono','descuentos','total_final'
    ];
}
