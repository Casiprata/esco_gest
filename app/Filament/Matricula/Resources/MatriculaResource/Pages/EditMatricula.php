<?php

namespace App\Filament\Matricula\Resources\MatriculaResource\Pages;

use App\Filament\Matricula\Resources\MatriculaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMatricula extends EditRecord
{
    protected static string $resource = MatriculaResource::class;

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
