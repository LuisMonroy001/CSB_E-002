<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminConfigController;
use App\Http\Controllers\Admin\ExcelImportController;
use App\Http\Controllers\Admin\ExcelUploadController;
use App\Http\Controllers\BonoController;
use App\Http\Controllers\BonusController;

// Página de inicio
Route::get('/', function () {
    return view('index');
})->name('home');

// --- Autenticación ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- Rutas protegidas ---
Route::middleware(['auth'])->group(function () {

    // PERFIL DE USUARIO NORMAL
    Route::prefix('perfil')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('perfil');
        Route::get('/consulta', [ProfileController::class, 'consulta'])->name('perfil.consulta');
        Route::get('/simulacion', [ProfileController::class, 'simulacion'])->name('perfil.simulacion');
        Route::get('/configuracion', [ProfileController::class, 'configuracion'])->name('perfil.configuracion');
        Route::get('/actividad', [ProfileController::class, 'actividad'])->name('perfil.actividad');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    });

    // PANEL DE ADMINISTRACIÓN
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminConfigController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/configuracion', [AdminConfigController::class, 'index'])->name('admin.configuracion');

        // Gestión de usuarios
        Route::delete('/usuarios/{id}', [AdminConfigController::class, 'destroy'])->name('usuarios.destroy');
        Route::put('/usuarios/{id}', [AdminConfigController::class, 'update'])->name('usuarios.update');

        // SUBIR ARCHIVO EXCEL
        Route::get('/subir_excel', [ExcelUploadController::class, 'show'])->name('admin.excel.form');
        Route::post('/subir_excel', [ExcelUploadController::class, 'store'])->name('admin.excel.upload');
        Route::post('/subir_excel/importar', [ExcelImportController::class, 'importarExcel'])->name('admin.excel.importar');
    });

    // --- Cálculo de bono ---
    // Antiguo (si aún lo necesitas)
    Route::post('/calcular-bono', [BonoController::class, 'calcular'])->name('calcular.bono');

    // Nuevo (con fórmulas Excel + AJAX en profile.blade.php)
    Route::post('/api/calcular-bono', [BonusController::class, 'calcular'])->name('api.calcular-bono');
});
