<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisciplinaProfessorTurma extends Model
{
    protected $table = 'disciplina_professor_turma';
    protected $fillable = ['disciplina_id', 'professor_id', 'turma_id'];
}
