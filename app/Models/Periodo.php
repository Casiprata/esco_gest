<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    protected $fillable = ['nome', 'descricao', 'ano_letivo_id'];

    public function turmas()
    {
        return $this->hasMany(Turma::class);
    }

    public function anoLetivo()
    {
        return $this->belongsTo(AnoLetivo::class);
    }
}
