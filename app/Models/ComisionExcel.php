<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComisionExcel extends Model
{
    protected $table = 'comisiones_excel';

    protected $fillable = [
        'archivo', 'hoja', 'email', 'row',
    ];

    protected $casts = [
        'row' => 'array',
    ];
}
