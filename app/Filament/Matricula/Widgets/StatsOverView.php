<?php

namespace App\Filament\Matricula\Widgets;

use App\Models\AnoLetivo;
use App\Models\Matricula;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverView extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Matrículas', Matricula::where('ano_letivo_id', AnoLetivo::latest('id')->value('id'))->count())
            ->description('Total de Matriculas')
            ->descriptionIcon('heroicon-s-document-text', IconPosition::Before)
            ->chart([7, 2, 18, 3, 15, 4, 17])
            ->color('warning'),
        ];
    }
}
