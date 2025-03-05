<?php

use App\Models\Categoria;
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
        Schema::create('professors', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->enum('genero', ['Masculino', 'Feminino']);
            $table->date('data_nascimento')->nullable();
            $table->string('naturalidade')->nullable();
            $table->string('morada')->nullable();
            $table->string('estado_civil')->nullable();
            $table->enum ('grau_academico', ['Técnico(a) Médio(a)', 'Licenciado(a)', 'Mestre', 'Doutor(a)'])->default('Técnico(a) Médio(a)')->nullable();
            $table->string('curso');
            $table->string('ano_conclusao');
            $table->foreignIdFor(Categoria::class)->constrained()->cascadeOnDelete();
            $table->string('funcao')->nullable();
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            $table->string('num_agente')->unique()->nullable();
            $table->string('bi')->nullable();
            $table->json('documentos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professors');
    }
};
