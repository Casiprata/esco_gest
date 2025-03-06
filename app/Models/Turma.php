<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    protected $fillable = ['nome', 'descricao', 'lotacao', 'classe_id', 'periodo_id', 'ano_letivo_id'];

    public function periodo()
    {
        return $this->belongsTo(Periodo::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function anoLetivo()
    {
        return $this->belongsTo(AnoLetivo::class);
    }

}
