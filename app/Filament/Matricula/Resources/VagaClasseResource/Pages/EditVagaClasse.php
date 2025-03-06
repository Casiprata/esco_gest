<?php

namespace App\Filament\Matricula\Resources\VagaClasseResource\Pages;

use App\Filament\Matricula\Resources\VagaClasseResource;
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
}
