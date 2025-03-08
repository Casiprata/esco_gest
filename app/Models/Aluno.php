<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    protected $table = 'alunos';

    protected $fillable = [
        'matricula_id',
        'responsavel_id',
    ];

    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }

    public function responsavel()
    {
        return $this->belongsTo(Responsavel::class);
    }
}
