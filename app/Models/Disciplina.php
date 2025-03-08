<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    protected $fillable = ['nome', 'descricao'];

    public function professores()
    {
        return $this->belongsToMany(Professor::class);
    }

    public function turmas()
    {
        return $this->belongsToMany(Turma::class, 'disciplina_professor_turma')
                    ->withPivot('professor_id');
    }

    


}
