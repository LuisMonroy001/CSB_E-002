<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\PagosImport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelImportController extends Controller
{
    public function importarExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new PagosImport, $request->file('file'));
            return back()->with('success', 'Archivo importado correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors('Error al importar el archivo: ' . $e->getMessage());
        }
    }
}
