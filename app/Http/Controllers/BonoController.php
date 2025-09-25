<?php

namespace App\Http\Controllers;

use App\Models\BonusCalculation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BonoController extends Controller
{
    public function calcular(Request $r)
    {
        $r->validate([
            'quincena'          => 'required|in:1QA,2QA',
            'meta_diaria'       => 'required|numeric|min:0',
            'dias_trabajados'   => 'required|integer|min:0',
            'total_avance_mes'  => 'required|numeric|min:0',
            'descuentos'        => 'nullable|numeric|min:0',
        ]);

        $user = Auth::user();

        // Datos de línea del usuario (asumo relación $user->agente->Linea)
        $linea = optional($user->agente)->Linea;

        // Busca en tabla "bolsa" por el rol/linea del usuario
        $bolsaRow = $linea
            ? DB::table('bolsa')->where('Rol', $linea)->first()
            : null;

        $porcentajeLinea = $bolsaRow?->Porcentaje ? (float)$bolsaRow->Porcentaje : 0;     // ej 6.7 => ¿viene % o factor?
        // Si Porcentaje viene como porcentaje (ej 6.7), conviértelo a factor:
        if ($porcentajeLinea > 1) {
            $porcentajeLinea = $porcentajeLinea / 100.0;
        }

        $bolsa1qa = $bolsaRow?->Bolsa_1QA ? (float)$bolsaRow->Bolsa_1QA : 0.0;
        $bolsa2qa = $bolsaRow?->BOLSA_1QA_2QA ? (float)$bolsaRow->BOLSA_1QA_2QA : 0.0;
        $bolsaBase = $r->quincena === '1QA' ? $bolsa1qa : $bolsa2qa;

        // Entradas
        $metaDiaria      = (float)$r->meta_diaria;
        $diasTrabajados  = (int)$r->dias_trabajados;
        $totalAvanceMes  = (float)$r->total_avance_mes;
        $descuentos      = (float)($r->descuentos ?? 0);

        // Cálculos
        $metaMensual   = $metaDiaria * $diasTrabajados;
        $cumplimiento  = $metaMensual > 0 ? ($totalAvanceMes / $metaMensual) * 100.0 : 0.0;
        $participacion = $porcentajeLinea; // usamos el de la tabla "bolsa" (factor)

        // Bono sin acelerador (reglas excel)
        if ($cumplimiento < 80) {
            $bonoSin = 0;
        } elseif ($cumplimiento < 100) {
            $bonoSin = $bolsaBase * $participacion * 0.8;
        } else {
            $bonoSin = $bolsaBase * $participacion;
        }

        // Bono con acelerador
        if ($cumplimiento > 120 && $cumplimiento <= 149) {
            $bonoAcel = $bonoSin * 1.2;
        } elseif ($cumplimiento >= 150) {
            $bonoAcel = $bonoSin * 1.5;
        } else {
            $bonoAcel = 0;
        }

        // Total de bono y quincenas
        $totalBono = max($bonoAcel, $bonoSin);
        $unoQA     = $totalBono * 0.5;              // mitad
        $dosQA     = $totalBono - $unoQA;           // la otra mitad
        $totalFinal= $totalBono - $descuentos;      // descuentos aplicados

        // Guardar registro
        $calc = BonusCalculation::create([
            'user_id'           => $user->id,
            'quincena'          => $r->quincena,
            'meta_diaria'       => $metaDiaria,
            'dias_trabajados'   => $diasTrabajados,
            'total_avance_mes'  => $totalAvanceMes,
            'descuentos'        => $descuentos,
            'linea'             => $linea,
            'porcentaje_linea'  => $participacion,
            'bolsa_1qa'         => $bolsa1qa,
            'bolsa_2qa'         => $bolsa2qa,
            'meta_mensual'      => $metaMensual,
            'cumplimiento'      => $cumplimiento,
            'participacion'     => $participacion,
            'bono_sin_acel'     => $bonoSin,
            'bono_con_acel'     => $bonoAcel,
            'total_bono'        => $totalBono,
            'uno_qa'            => $unoQA,
            'dos_qa'            => $dosQA,
            'total_final'       => $totalFinal,
        ]);

        // Responder JSON (para pintar sin recarga)
        return response()->json([
            'ok' => true,
            'calc' => [
                'id'                 => $calc->id,
                'quincena'           => $calc->quincena,
                'linea'              => $calc->linea,
                'porcentaje_linea'   => $calc->porcentaje_linea,
                'bolsa_1qa'          => $calc->bolsa_1qa,
                'bolsa_2qa'          => $calc->bolsa_2qa,
                'meta_mensual'       => $calc->meta_mensual,
                'cumplimiento'       => $calc->cumplimiento,
                'participacion'      => $calc->participacion,
                'bono_sin_acel'      => $calc->bono_sin_acel,
                'bono_con_acel'      => $calc->bono_con_acel,
                'total_bono'         => $calc->total_bono,
                'uno_qa'             => $calc->uno_qa,
                'dos_qa'             => $calc->dos_qa,
                'descuentos'         => $calc->descuentos,
                'total_final'        => $calc->total_final,
                'created_at'         => $calc->created_at->toDateTimeString(),
            ],
        ]);
    }
}
