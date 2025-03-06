<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ensino extends Model
{
    protected $fillable = ['nome', 'descricao', 'ano_letivo_id'];

    public function classes()
    {
        return $this->hasMany(Classe::class);
    }

    public function anoLetivo()
    {
        return $this->belongsTo(AnoLetivo::class);
    }
}
