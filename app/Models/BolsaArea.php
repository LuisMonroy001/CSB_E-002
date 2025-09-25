<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BolsaArea extends Model
{
    protected $table = 'bolsa_area';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $appends = ['porcentaje_decimal'];

    public function getPorcentajeDecimalAttribute()
    {
        $raw = (float) ($this->attributes['Porcentaje'] ?? 0);
        return $raw > 1 ? $raw / 100 : $raw; // 21.00 -> 0.21
    }
}
