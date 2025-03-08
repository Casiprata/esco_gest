<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'data_nascimento',
        'naturalidade',
        'pai',
        'mae',
        'ano_letivo_id',
        'classe_id',
        'turma_id',
        'data_matricula',
        'foto',
        'documentos',
        'estado',
        'observacoes',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'data_matricula' => 'date',
        'documentos' => 'array',
    ];

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function anoLetivo()
    {
        return $this->belongsTo(AnoLetivo::class);
    }



    protected static function booted()
    {
        static::created(function ($matricula) {
            // Criar aluno automaticamente ao cadastrar uma matrícula
            Aluno::create(['matricula_id' => $matricula->id]);
        });

        static::deleted(function ($matricula) {
            // Remover aluno automaticamente ao deletar a matrícula
            $matricula->aluno()->delete();
        });
    }

    public function aluno()
    {
        return $this->hasOne(Aluno::class);
    }


}
