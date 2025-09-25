<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BolsaMensual extends Model
{
    protected $table = 'bolsa_mensual';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $appends = ['uno_qa', 'dos_qa'];

    public function getUnoQaAttribute()
    {
        return (float) ($this->attributes['1QA'] ?? 0);
    }

    public function getDosQaAttribute()
    {
        return (float) ($this->attributes['2QA'] ?? 0);
    }
}
