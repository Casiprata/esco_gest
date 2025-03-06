<?php

namespace App\Filament\Matricula\Resources\MatriculaResource\Pages;

use App\Filament\Matricula\Resources\MatriculaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMatricula extends CreateRecord
{
    protected static string $resource = MatriculaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
