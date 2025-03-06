<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MatriculaResource\Pages;
use App\Filament\Resources\MatriculaResource\RelationManagers;
use App\Models\AnoLetivo;
use App\Models\Matricula;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MatriculaResource extends Resource
{
    protected static ?string $model = Matricula::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('ano_letivo_id')
                    ->label('Ano Letivo')
                    ->options(AnoLetivo::all()->pluck('nome', 'id'))
                    ->live()
                    ->required(),
                Forms\Components\TextInput::make('nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('data_nascimento'),
                Forms\Components\TextInput::make('naturalidade')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('pai')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('mae')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('ano_letivo')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('classe_id')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('data_matricula')
                    ->required(),
                Forms\Components\TextInput::make('foto')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('documentos')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('estado')
                    ->required(),
                Forms\Components\Textarea::make('observacoes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('data_nascimento')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('naturalidade')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pai')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mae')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ano_letivo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('classe_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('data_matricula')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('foto')
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
            'index' => Pages\ListMatriculas::route('/'),
            'create' => Pages\CreateMatricula::route('/create'),
            'edit' => Pages\EditMatricula::route('/{record}/edit'),
        ];
    }
}
