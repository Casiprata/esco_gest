<?php

namespace App\Filament\Widgets;

use App\Models\AnoLetivo;
use App\Models\Matricula;
use Filament\Widgets\ChartWidget;

class ChartMatriculasAno extends ChartWidget
{
    protected static ?string $heading = '📊 Matrículas por Ano Letivo';

    protected function getData(): array
    {
        $matriculasPorAno = Matricula::selectRaw('ano_letivo_id, COUNT(*) as total')
            ->groupBy('ano_letivo_id')
            ->pluck('total', 'ano_letivo_id')
            ->toArray();

        // Buscar os nomes dos anos letivos
        $anos = AnoLetivo::whereIn('id', array_keys($matriculasPorAno))
            ->pluck('ano_letivo', 'id')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Matrículas',
                    'data' => array_values($matriculasPorAno),
                    'backgroundColor' => [
                        '#4CAF50', // Verde
                        '#2196F3', // Azul
                        '#FF9800', // Laranja
                        '#E91E63', // Rosa
                        '#9C27B0', // Roxo
                    ],
                    'borderColor' => '#ffffff',
                    'borderWidth' => 2,
                    'borderRadius' => 8,
                    'hoverBackgroundColor' => '#FFC107', // Amarelo ao passar o mouse
                    'hoverBorderColor' => '#000',
                ],
            ],
            'labels' => array_values($anos),
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Pode mudar para 'line', 'doughnut', etc.
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                    'labels' => [
                        'color' => '#333', // Cor do texto da legenda
                        'font' => [
                            'size' => 14,
                            'weight' => 'bold',
                        ],
                    ],
                ],
                'tooltip' => [
                    'enabled' => true,
                    'backgroundColor' => '#222',
                    'titleFont' => [
                        'size' => 14,
                        'weight' => 'bold',
                    ],
                    'bodyFont' => [
                        'size' => 12,
                    ],
                ],
            ],
            'scales' => [
                'x' => [
                    'grid' => [
                        'display' => false, // Remove as linhas do grid no eixo X
                    ],
                    'ticks' => [
                        'color' => '#444',
                        'font' => [
                            'size' => 12,
                        ],
                    ],
                ],
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'color' => 'rgba(200, 200, 200, 0.3)', // Linhas suaves no eixo Y
                    ],
                    'ticks' => [
                        'color' => '#444',
                        'font' => [
                            'size' => 12,
                        ],
                    ],
                ],
            ],
        ];
    }
}

