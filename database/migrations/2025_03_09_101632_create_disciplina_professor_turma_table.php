<?php

use App\Models\Disciplina;
use App\Models\Professor;
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
        Schema::create('disciplina_professor_turma', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Turma::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Professor::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Disciplina::class)->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disciplina_professor_turma');
    }
};
