<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelImportController extends Controller
{
    public function importarExcel(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
            'replace_previous' => ['nullable', 'boolean'],
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        $nombreArchivo = $file->getClientOriginalName();
        $replacePrevious = (bool) $request->boolean('replace_previous');

        // ========= Helpers =========
        $normalizeHeader = function (?string $h): string {
            $h = trim((string)$h);
            // a minúsculas
            $h = mb_strtolower($h, 'UTF-8');
            // quitar acentos
            $h = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $h);
            // colapsar espacios
            $h = preg_replace('/\s+/', ' ', $h);
            // quitar caracteres no deseados de extremos
            $h = trim($h, " \t\n\r\0\x0B");
            return $h; // p.ej. "días trabajados" -> "dias trabajados"
        };

        // Mapeo: encabezado Excel normalizado => columna BD
        // AJUSTA las claves de la izquierda a tus encabezados reales (pero normalizados).
        $map = [
            'linea'                               => 'linea',
            'no. empleado'                        => 'no_empleado',
            'agente'                              => 'agente',
            'meta diaria'                         => 'meta_diaria',
            'dias trabajados'                     => 'dias_trabajados',
            'meta mes'                            => 'meta_mes',
            'total avance mes'                    => 'total_avance_mes',
            '% cumplimiento'                      => 'cumplimiento_meta',
            'productividad'                       => 'productividad',
            'participacion'                       => 'participacion',
            'bono sin acelerador'                 => 'bono_sin_acelerador',
            'bono con acelerador'                 => 'bono_con_acelerador',
            'total bono'                          => 'total_bono',
            'canal lineas internas'               => 'canal_lineas_internas',
            'venta tmk'                           => 'venta_tmk',
            'biometricos'                         => 'biometricos',
            'seguros'                             => 'seguros',
            'referido'                            => 'referido',
            'afectacion calidad / incidencias'    => 'afectacion_calidad_o_incidencias',
            'ajuste periodo anterior'             => 'ajuste_periodo_anterior',
            'descuentos'                          => 'descuentos',
            'instructor'                          => 'instructor',
            '1qa'                                 => 'uno_qa',
            '2qa'                                 => 'dos_qa',
            'total'                               => 'total',
            '% bolsa'                             => 'porcentaje_bolsa',
            'bolsa 1qa'                           => 'bolsa_uno_qa',
            'bolsa 2qa'                           => 'bolsa_dos_qa',
            'total variable'                      => 'total_variable',
            'diferencia presupuesto'              => 'diferencia_presupuesto',
            'email'                               => 'email',
        ];

        // Columnas DECIMAL en BD (asegura que todas las numéricas estén listadas aquí)
        $numericCols = [
            'meta_diaria','dias_trabajados','meta_mes','total_avance_mes','cumplimiento_meta',
            'productividad','participacion','bono_sin_acelerador','bono_con_acelerador','total_bono',
            'canal_lineas_internas','venta_tmk','biometricos','seguros','referido',
            'afectacion_calidad_o_incidencias','ajuste_periodo_anterior','descuentos',
            'uno_qa','dos_qa','total','porcentaje_bolsa','bolsa_uno_qa','bolsa_dos_qa',
            'total_variable','diferencia_presupuesto',
        ];

        $toNumeric = function ($value) {
            if (is_null($value) || $value === '') return 0.0; // usa null si prefieres
            if (is_numeric($value)) return (float)$value;

            if (is_string($value)) {
                $raw = trim($value);
                // quitar símbolos de moneda/espacios
                $raw = preg_replace('/[^\d,.\-]/', '', $raw);

                // 12.345,67 -> 12345.67
                if (preg_match('/^-?\d{1,3}(\.\d{3})+(,\d+)?$/', $raw)) {
                    $raw = str_replace(['.', ','], ['', '.'], $raw);
                }
                // 12,345.67 -> 12345.67
                elseif (preg_match('/^-?\d{1,3}(,\d{3})+(\.\d+)?$/', $raw)) {
                    $raw = str_replace([','], [''], $raw);
                }

                if (is_numeric($raw)) return (float)$raw;
            }

            // Si no se puede convertir, devolvemos 0.0 (o null si tu columna lo permite)
            return 0.0;
        };

        $normalizeEmail = function ($value) {
            if ($value === null) return null;
            $value = strtolower(trim((string)$value));
            return filter_var($value, FILTER_VALIDATE_EMAIL) ? $value : null;
        };

        $isSummaryRow = function (array $values): bool {
            $joined = mb_strtolower(implode(' ', array_map(fn($v) => trim((string)$v), $values)), 'UTF-8');
            // cualquier aparición de estas palabras clave dispara salto de fila
            return Str::contains($joined, ['sumatoria', 'subtotal', 'total', 'totales']);
        };

        $rowsRead = 0; $rowsSaved = 0; $rowsSkipped = 0;

        DB::beginTransaction();
        try {
            $spreadsheet = IOFactory::load($path);

            foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
                $rows = $sheet->toArray(null, true, true, true); // A,B,C...

                if (empty($rows) || count($rows) < 2) {
                    continue;
                }

                // Encabezados normalizados (por columna A,B,C,...)
                $headers = [];
                foreach ($rows[1] as $col => $text) {
                    $headers[$col] = $normalizeHeader($text);
                }

                $highestRow = count($rows);

                for ($r = 2; $r <= $highestRow; $r++) {
                    $rowsRead++;
                    $fila = $rows[$r] ?? [];

                    // Saltar filas vacías
                    $allEmpty = true;
                    foreach ($fila as $v) {
                        if (!is_null($v) && trim((string)$v) !== '') { $allEmpty = false; break; }
                    }
                    if ($allEmpty) { $rowsSkipped++; continue; }

                    // Saltar filas "Sumatoria/Total/Subtotal"
                    if ($isSummaryRow(array_values($fila))) { $rowsSkipped++; continue; }

                    // Construir récord => columnas BD
                    $record = [];

                    foreach ($headers as $col => $headerNorm) {
                        // Si el encabezado no está mapeado, lo ignoramos
                        if (!array_key_exists($headerNorm, $map)) continue;

                        $dbCol = $map[$headerNorm];
                        $val   = $fila[$col] ?? null;

                        if ($dbCol === 'email') {
                            $record[$dbCol] = $normalizeEmail($val);
                        } elseif (in_array($dbCol, $numericCols, true)) {
                            $record[$dbCol] = $toNumeric($val); // 0.0 si no es numérico
                        } else {
                            $record[$dbCol] = is_null($val) ? null : trim((string)$val);
                        }
                    }

                    // (Opcional) si tu BD requiere email no nulo, saltar si falta
                    if (array_key_exists('email', $record) && empty($record['email'])) {
                        $rowsSkipped++; continue;
                    }

                    $record['created_at'] = now();
                    $record['updated_at'] = now();

                    DB::table('pagos_excel')->insert($record);
                    $rowsSaved++;
                }
            }

            DB::commit();

            return back()
                ->with('success', 'Archivo importado y guardado correctamente.')
                ->with('import_summary', [
                    'archivo'      => $nombreArchivo,
                    'hoja'         => 'Todas (procesadas)',
                    'rows_read'    => $rowsRead,
                    'rows_saved'   => $rowsSaved,
                    'rows_skipped' => $rowsSkipped,
                ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors('Error al importar el archivo: ' . $e->getMessage());
        }
    }
}
