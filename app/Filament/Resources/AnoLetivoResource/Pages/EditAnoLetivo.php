<?php

namespace App\Filament\Resources\AnoLetivoResource\Pages;

use App\Filament\Resources\AnoLetivoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnoLetivo extends EditRecord
{
    protected static string $resource = AnoLetivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
