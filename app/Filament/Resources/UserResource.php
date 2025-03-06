<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $label = 'Usuários';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->label('Nome')
                ->required()
                ->maxLength(255),

            TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),

            TextInput::make('password')
                ->label('Palavra-Passe')
                ->password()
                ->required()
                ->maxLength(255),

            Select::make('role')
                ->label('Papel')
                ->required()
                ->options([
                    'RESPONSAVEL' => 'RESPONSÁVEL',
                    'PROFESSOR'=> 'PROFESSOR',
                    'MATRICULA' => 'MATRÍCULA',
                    'GESTOR' => 'GESTOR',
                    'ADMIN' => 'ADMIN',

                ])
                ->default('ESTUDANTE')
                //->disabled(auth()->user()->isBibliotecario()), // Bloqueia para bibliotecário
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->modifyQueryUsing(fn(Builder $query) =>
            auth()->user()->role === 'BIBLIOTECARIO'
                ? $query->whereIn('role', ['ESTUDANTE', 'PROFESSOR'])
                : $query
        )
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Nome')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('email')
                    ->searchable(),

                TextColumn::make('role')
                    ->label('Acesso')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'ADMIN' => 'primary',
                        'RESPONSAVEL' => 'success',
                        'PROFESSOR' => 'danger',
                        'MATRICULA' => 'info',
                        'GESTOR' => 'warning',
                    }),

                TextColumn::make('created_at')
                    ->label('Data de Criação')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Editar')
                    ->color('warning')
                    ->icon('heroicon-o-pencil-square'),
                    //->hidden(fn ($record) => auth()->user()->role === 'BIBLIOTECARIO' && $record->role === 'PROFESSOR'),
                Tables\Actions\DeleteAction::make()
                    ->label('Eliminar')
                    ->color('danger')
                    ->icon('heroicon-o-trash'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
