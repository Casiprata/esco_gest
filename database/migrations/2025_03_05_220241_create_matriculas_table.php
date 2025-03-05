<?php

use App\Models\Aluno;
use App\Models\Classe;
use App\Models\Periodo;
use App\Models\Turma;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('matriculas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->date('data_nascimento')->nullable();
            $table->string('naturalidade')->nullable();
            $table->string('pai')->nullable();
            $table->string('mae')->nullable();
            $table->string('ano_letivo');
            $table->foreignIdFor(Periodo::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Classe::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Turma::class)->constrained()->cascadeOnDelete();
            $table->date('data_matricula');
            $table->string('foto')->nullable();
            $table->json('documentos')->nullable();
            $table->enum('estado', ['Pendente', 'Autorizada', 'Cancelada'])->default('Pendente');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matriculas');
    }
};
