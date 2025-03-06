<?php

namespace App\Filament\Resources\AnoLetivoResource\Pages;

use App\Filament\Resources\AnoLetivoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAnoLetivo extends CreateRecord
{
    protected static string $resource = AnoLetivoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
