<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EnsinoResource\Pages;
use App\Filament\Resources\EnsinoResource\RelationManagers;
use App\Models\AnoLetivo;
use App\Models\Ensino;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EnsinoResource extends Resource
{
    protected static ?string $model = Ensino::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

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
                Forms\Components\TextInput::make('nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('descricao')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->searchable(),
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
            'index' => Pages\ListEnsinos::route('/'),
            'create' => Pages\CreateEnsino::route('/create'),
            'edit' => Pages\EditEnsino::route('/{record}/edit'),
        ];
    }
}
