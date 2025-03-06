<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VagaClasse extends Model
{
    protected $fillable = ['classe_id', 'quantidade', 'estado', 'ano_letivo_id'];

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function anoLetivo()
    {
        return $this->belongsTo(AnoLetivo::class);
    }
}
