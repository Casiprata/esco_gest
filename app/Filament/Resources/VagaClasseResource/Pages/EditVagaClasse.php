<?php

namespace App\Filament\Resources\VagaClasseResource\Pages;

use App\Filament\Resources\VagaClasseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVagaClasse extends EditRecord
{
    protected static string $resource = VagaClasseResource::class;

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
