<?php

namespace App\Filament\Widgets;

use App\Models\AnoLetivo;
use App\Models\Classe;
use App\Models\Matricula;
use Filament\Widgets\ChartWidget;

class ChartMatriculasClasse extends ChartWidget
{
    protected static ?string $heading = '🏫 Matrículas por Classe (Último Ano Letivo)';

    protected function getData(): array
    {
        $ultimoAnoId = AnoLetivo::latest('id')->value('id');

        $matriculasPorClasse = Matricula::where('ano_letivo_id', $ultimoAnoId)
            ->selectRaw('classe_id, COUNT(*) as total')
            ->groupBy('classe_id')
            ->pluck('total', 'classe_id')
            ->toArray();

        $classes = Classe::whereIn('id', array_keys($matriculasPorClasse))
            ->pluck('nome', 'id')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Matrículas',
                    'data' => array_values($matriculasPorClasse),
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', '#9C27B0'],
                    'hoverOffset' => 10,
                ],
            ],
            'labels' => array_values($classes),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
