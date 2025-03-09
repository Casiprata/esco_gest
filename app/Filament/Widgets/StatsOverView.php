<?php

namespace App\Filament\Widgets;

use App\Models\Aluno;
use App\Models\AnoLetivo;
use App\Models\Professor;
use App\Models\Turma;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverView extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Professores', Professor::query()->count())
            ->description('Total de Professores')
            ->descriptionIcon('heroicon-s-user', IconPosition::Before)
            ->chart([7, 2, 18, 3, 15, 4, 17])
            ->color('warning'),
            Stat::make('Alunos', function () {
                $ultimoAnoLetivo = AnoLetivo::latest('id')->value('id'); // Obtém o último ano letivo
                return Aluno::whereHas('matricula', function ($query) use ($ultimoAnoLetivo) {
                    $query->where('ano_letivo_id', $ultimoAnoLetivo);
                })->count();
            })
            ->description('Alunos Matriculados')
            ->descriptionIcon('heroicon-s-users', IconPosition::Before)
            ->chart([0,0,2,7,3,10])
            ->color('success'),
            Stat::make('Turmas', Turma::query()->count())
            ->description('Total de Turmas')
            ->descriptionIcon('heroicon-s-table-cells', IconPosition::Before)
            ->chart([20,0,2,7,3,10])
            ->color('danger'),
        ];
    }
}
