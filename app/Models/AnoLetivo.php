<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnoLetivo extends Model
{
    protected $fillable = ['ano_letivo', 'estado', 'data_inicio', 'data_fim'];

    public function classes()
    {
        return $this->hasMany(Classe::class);
    }

    public function ensinos()
    {
        return $this->hasMany(Ensino::class);
    }

    public function periodos()
    {
        return $this->hasMany(Periodo::class);
    }
}
