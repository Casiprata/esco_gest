<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    protected $fillable = ['nome', 'descricao'];

    public function professores()
    {
        return $this->belongsToMany(Professor::class, 'disciplina_professor_turma')
                    ->withPivot('turma_id');
    }

}
