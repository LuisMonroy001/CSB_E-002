<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroBono extends Model
{
    use HasFactory;

    protected $table = 'registro_bono';

    protected $fillable = [
        'Linea','No_empleados','Agente','Meta_Diaria','D_Trabajados','Meta_Mes',
        'Total_Avance','Cumplimiento_Meta','Productividad','Participacion',
        'Bono_sin_Acelerador','Bono_con_Acelerador','Total_Bono',
        'Canales_Lineas_Internas','Venta_TMK','Biometricos','Seguros','Referido',
        'Calidad','Ajuste_Periodo_Anterior','Descuentos','Instructor',
        '1QA','2QA','Total','email'
    ];
}
