<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    protected $table = 'professors';

    protected $fillable = [
        'nome',
        'num_agente',
        'genero',
        'data_nascimento',
        'naturalidade',
        'morada',
        'estado_civil',
        'grau_academico',
        'curso',
        'ano_conclusao',
        'funcao',
        'email',
        'telefone',
        'bi',
        'documentos',
        'categoria_id',
        'user_id',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'documentos' => 'array',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function disciplinas()
    {
        return $this->belongsToMany(Disciplina::class, 'disciplina_professor', 'professor_id', 'disciplina_id');
    }

    public function turmas()
    {
        return $this->belongsToMany(Turma::class, 'disciplina_professor_turma')
                    ->withPivot('disciplina_id');
    }
}
