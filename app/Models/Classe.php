<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    protected $fillable = ['nome', 'descricao', 'ensino_id', 'ano_letivo_id'];

    public function ensino()
    {
        return $this->belongsTo(Ensino::class);
    }

    public function turmas()
    {
        return $this->hasMany(Turma::class);
    }

    public function anoLetivo()
    {
        return $this->belongsTo(AnoLetivo::class);
    }
}
