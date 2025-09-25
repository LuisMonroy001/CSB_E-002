<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BonusController;

// --- API protegida con Sanctum (SPA) ---
// Requiere tener Sanctum configurado: SANCTUM_STATEFUL_DOMAINS, SESSION_DOMAIN, etc.
Route::middleware('auth:sanctum')->post('/calcular-bono', [BonusController::class, 'calcular'])
    ->name('api.calcular-bono');
