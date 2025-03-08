<?php

namespace App\Filament\Professor\Resources;

use App\Filament\Professor\Resources\ProfessorResource\Pages;
use App\Filament\Professor\Resources\ProfessorResource\RelationManagers;
use App\Models\Professor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProfessorResource extends Resource
{
    protected static ?string $model = Professor::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('genero')
                    ->required(),
                Forms\Components\DatePicker::make('data_nascimento'),
                Forms\Components\TextInput::make('naturalidade')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('morada')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('estado_civil')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('grau_academico')
                    ->required(),
                Forms\Components\TextInput::make('curso')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('ano_conclusao')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('categoria_id')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('funcao')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('telefone')
                    ->tel()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('num_agente')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('bi')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('documentos')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('genero'),
                Tables\Columns\TextColumn::make('data_nascimento')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('naturalidade')
                    ->searchable(),
                Tables\Columns\TextColumn::make('morada')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado_civil')
                    ->searchable(),
                Tables\Columns\TextColumn::make('grau_academico'),
                Tables\Columns\TextColumn::make('curso')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ano_conclusao')
                    ->searchable(),
                Tables\Columns\TextColumn::make('categoria_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('funcao')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telefone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('num_agente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bi')
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
            'index' => Pages\ListProfessors::route('/'),
            'create' => Pages\CreateProfessor::route('/create'),
            'edit' => Pages\EditProfessor::route('/{record}/edit'),
        ];
    }
}
