<?php

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
        Schema::create('ano_letivos', function (Blueprint $table) {
            $table->id();
            $table->string('ano_letivo')->unique();
            $table->enum('estado', ['Aberto', 'Fechado'])->default('Aberto');
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ano_letivos');
    }
};
