<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BonusController extends Controller
{
    public function calcular(Request $request)
    {
        // 1) Validación
        $data = $request->validate([
            'quincena'               => 'required|in:1QA,2QA',
            'meta_diaria'            => 'required|numeric|min:0',   // D
            'dias_trabajados'        => 'required|integer|min:0',   // E
            'total_avance_mes'       => 'required|numeric|min:0',   // G
            'avance_total_mes_linea' => 'required|numeric|min:0',   // $G$23
            'descuentos'             => 'nullable|numeric|min:0',
        ]);

        // 2) Entradas
        $quincena   = $data['quincena'];
        $D          = (float) $data['meta_diaria'];
        $E          = (int)   $data['dias_trabajados'];
        $G          = (float) $data['total_avance_mes'];        // Avance del agente
        $G23        = (float) $data['avance_total_mes_linea'];  // Avance total de la línea ($G$23)
        $descuentos = (float) ($data['descuentos'] ?? 0);

        // 3) Usuario (para mostrar la Línea)
        $user  = $request->user();
        $linea = optional($user->agente)->Linea ?? 'SIN LINEA';

        // Porcentaje de línea (informativo)
        $rowLinea = DB::table('bolsa_area')->where('Linea', $linea)->first();
        $porcentajeLinea = $rowLinea ? ((float)$rowLinea->Porcentaje / 100) : null; // 21 → 0.21 (factor)

        // 4) Bolsa mensual (último registro)
        $bolsaRow = DB::table('bolsa_mensual')->orderBy('id', 'desc')->first();
        if (!$bolsaRow) {
            return response()->json([
                'ok'      => false,
                'message' => 'No hay registros en bolsa_mensual.',
            ], 422);
        }

        $bolsa1 = (float) ($bolsaRow->{'1QA'} ?? 0);
        $bolsa2 = (float) ($bolsaRow->{'2QA'} ?? 0);
        $bolsaTotal = $bolsa1 + $bolsa2;

        // AB2 = bolsa base según la quincena elegida
        $AB2 = ($quincena === '1QA') ? $bolsa1 : $bolsa2;

        // 5) Cálculos
        // F = Meta mes
        $F = $D * max($E, 0);

        // H = Cumplimiento % (solo informativo)
        $H = ($F > 0) ? ($G / $F) * 100.0 : 0.0;

        // J = Productividad (G/E)
        $J = ($E > 0) ? ($G / $E) : 0.0;

        // K = Participación (G / $G$23) como factor 0..1
        $K = ($G23 > 0) ? ($G / $G23) : 0.0;

        // ======== PAGO SIEMPRE (sin mínimos): Base = AB2 * K ========
        $L = $AB2 * $K;

        // Acelerador opcional por J (si no quieres acelerador, pon $M = 0)
        if ($J > 8.5 && $J <= 9.5) {
            $M = $L * 1.20;
        } elseif ($J > 9.5) {
            $M = $L * 1.50;
        } else {
            $M = 0.0;
        }

        // Total bono (si hay acelerador, usa el mayor)
        $N = ($M > 0) ? $M : $L;

        // 6) Distribución por quincena (informativo)
        $unoQA = ($quincena === '1QA') ? $N : 0.0;
        $dosQA = ($quincena === '2QA') ? $N : 0.0;

        // Pago final de la quincena seleccionada (no negativo)
        $totalFinal = max(0.0, $N - $descuentos);

        // 7) Guardar en historial
        DB::table('simulaciones_bono')->insert([
            'user_id'       => $user->id,
            'linea'         => $linea,
            'quincena'      => $quincena,
            'meta_mensual'  => $F,
            'total_avance'  => $G,
            'cumplimiento'  => $H,
            'participacion' => $K,
            'bono'          => $N,
            'descuentos'    => $descuentos,
            'total_final'   => $totalFinal,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // 8) Respuesta JSON
        return response()->json([
            'ok'   => true,
            'calc' => [
                'mes'                 => now()->translatedFormat('F Y'),
                'quincena'            => $quincena,
                'linea'               => $linea,
                'porcentaje_linea'    => $porcentajeLinea, // factor (0.21) -> en Blade ya lo multiplicas por 100
                'bolsa_linea_1qa'     => $bolsa1,
                'bolsa_linea_2qa'     => $bolsa2,
                'bolsa_linea_total'   => $bolsaTotal,
                'bolsa_base_usada'    => $AB2,
                'meta_mensual'        => $F,
                'total_avance'        => $G,
                'g23'                 => $G23,
                'cumplimiento'        => $H,
                'productividad'       => $J,
                'participacion'       => $K,
                'bono_sin_acel'       => $L,
                'bono_con_acel'       => $M,
                'total_bono'          => $N,
                'uno_qa'              => $unoQA,
                'dos_qa'              => $dosQA,
                'descuentos'          => $descuentos,
                'total_final'         => $totalFinal,
            ],
        ]);
    }
}
