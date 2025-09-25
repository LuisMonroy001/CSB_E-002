<?php

namespace App\Imports;

use App\Models\PagosExcel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class PagosImport implements ToModel, WithHeadingRow, WithCalculatedFormulas
{
    public function model(array $row)
    {
        // Normaliza claves a minúsculas y quita espacios
        $row = array_change_key_case(array_map(function ($value) {
            // Si viene una fórmula en texto, la ignoramos
            if (is_string($value) && str_starts_with($value, '=')) {
                return null;
            }

            return is_string($value) ? trim($value) : $value;
        }, $row), CASE_LOWER);

        return new PagosExcel([
            'linea'                              => $row['linea'] ?? null,
            'no_empleado'                        => $row['no_empleado'] ?? null,
            'agente'                             => $row['agente'] ?? null,
            'meta_diaria'                        => $row['meta_diaria'] ?? null,
            'dias_trabajados'                    => $row['d_trabajados'] ?? null,
            'meta_mes'                           => $row['meta_mes'] ?? null,
            'total_avance_mes'                   => $row['total_avance_mes'] ?? null,
            'cumplimiento_meta'                  => $row['cumplimiento_meta'] ?? null,
            'productividad'                      => $row['productividad'] ?? null,
            'participacion'                      => $row['participacion'] ?? null,
            'bono_sin_acelerador'                => $row['bono_sin_acelerador'] ?? null,
            'bono_con_acelerador'                => $row['bono_con_acelerador'] ?? null,
            'total_bono'                         => $row['total_bono'] ?? null,
            'canal_lineas_internas'              => $row['canal_lineas_internas'] ?? null,
            'venta_tmk'                          => $row['venta_tmk'] ?? null,
            'biometricos'                        => $row['biometricos'] ?? null,
            'seguros'                            => $row['seguros'] ?? null,
            'referido'                           => $row['referido'] ?? null,
            'afectacion_calidad_o_incidencias'   => $row['afectacion_calidad_o_incidencias'] ?? null,
            'ajuste_periodo_anterior'            => $row['ajuste_periodo_anterior'] ?? null,
            'descuentos'                         => $row['descuentos'] ?? null,
            'instructor'                         => $row['instructor'] ?? null,
            'uno_qa'                             => $row['1qa'] ?? null,
            'dos_qa'                             => $row['2qa'] ?? null,
            'total'                              => $row['total'] ?? null,
            'porcentaje_bolsa'                   => $row['%_bolsa'] ?? null,
            'bolsa_uno_qa'                       => $row['bolsa_1qa'] ?? null,
            'bolsa_dos_qa'                       => $row['bolsa_2qa'] ?? null,
            'total_variable'                     => $row['total_variable'] ?? null,
            'diferencia_presupuesto'             => $row['diferencia_presupuesto'] ?? null,
            'email'                              => $row['email'] ?? null,
        ]);
    }
}
