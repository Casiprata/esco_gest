<?php

namespace App\Filament\Resources\VagaClasseResource\Pages;

use App\Filament\Resources\VagaClasseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateVagaClasse extends CreateRecord
{
    protected static string $resource = VagaClasseResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
