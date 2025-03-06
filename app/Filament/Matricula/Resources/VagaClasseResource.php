<?php

namespace App\Filament\Matricula\Resources;

use App\Filament\Matricula\Resources\VagaClasseResource\Pages;
use App\Filament\Matricula\Resources\VagaClasseResource\RelationManagers;
use App\Models\VagaClasse;
use Filament\Forms;
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
                Forms\Components\TextInput::make('classe_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('ano_letivo')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('quantidade')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('estado')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('classe_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ano_letivo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantidade')
                    ->numeric()
                    ->sortable(),
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
