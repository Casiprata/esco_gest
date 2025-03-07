<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VagaClasseResource\Pages;
use App\Filament\Resources\VagaClasseResource\RelationManagers;
use App\Models\AnoLetivo;
use App\Models\Classe;
use App\Models\VagaClasse;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VagaClasseResource extends Resource
{
    protected static ?string $model = VagaClasse::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('ano_letivo_id')
                ->label('Ano Letivo')
                ->options(AnoLetivo::pluck('ano_letivo', 'id')) // Garante que todas as opções estejam disponíveis
                ->live()
                ->default(fn () => AnoLetivo::latest('id')->value('id')) // Apenas seleciona o último ano letivo como padrão
                ->required(),
                Select::make('classe_id')
                ->label('Classes')
                    ->options(Classe::all()->pluck('nome', 'id'))
                    ->live()
                    ->required(),
                TextInput::make('quantidade')
                    ->required()
                    ->numeric(),
                Select::make('estado')
                    ->options(
                        [
                            'Aberta' => 'Aberta',
                            'Fechada' => 'Fechada',
                            ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('classe.nome')
                    ->label('Classe')
                    ->searchable()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('anoLetivo.ano_letivo')
                    ->label('Ano Letivo')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantidade')
                    ->label('Nº de Vagas')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVagaClasses::route('/'),
            'create' => Pages\CreateVagaClasse::route('/create'),
            'edit' => Pages\EditVagaClasse::route('/{record}/edit'),
        ];
    }
}
