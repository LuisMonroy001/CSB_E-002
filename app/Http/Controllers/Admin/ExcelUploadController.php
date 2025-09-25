<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PagosImport;

class ExcelUploadController extends Controller
{
    public function show()
    {
        return view('admin.upload_excel');
    }

    public function store(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls'
        ]);

        Excel::import(new PagosImport, $request->file('archivo'));

        return back()->with('status', 'Archivo cargado correctamente');
    }
}
