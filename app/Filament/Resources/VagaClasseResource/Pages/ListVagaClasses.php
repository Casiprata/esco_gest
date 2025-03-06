<?php

namespace App\Filament\Resources\VagaClasseResource\Pages;

use App\Filament\Resources\VagaClasseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVagaClasses extends ListRecords
{
    protected static string $resource = VagaClasseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
