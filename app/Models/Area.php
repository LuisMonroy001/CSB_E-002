<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'area'; // nombre real en tu BD
    public $timestamps = false; // si no usas created_at y updated_at
}
