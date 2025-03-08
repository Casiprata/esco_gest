<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProfessorResource\Pages;
use App\Filament\Resources\ProfessorResource\RelationManagers;
use App\Models\Categoria;
use App\Models\Professor;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProfessorResource extends Resource
{
    protected static ?string $model = Professor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $modelLabel = 'Professores';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('user_id'),
                TextInput::make('nome')
                    ->required()
                    ->maxLength(255),
                TextInput::make('num_agente')
                    ->maxLength(255)
                    ->default(null),
                Select::make('genero')
                    ->options([
                        'Masculino' => 'Masculino',
                        'Feminino' => 'Feminino',
                    ])
                    ->required(),
                DatePicker::make('data_nascimento')
                    ->label('Data de Nascimento'),
                TextInput::make('naturalidade')
                    ->maxLength(255)
                    ->default(null),
                TextInput::make('morada')
                    ->maxLength(255)
                    ->default(null),
                Select::make('estado_civil')
                    ->options([
                        'Solteiro(a)' => 'Solteiro(a)',
                        'Casado(a)' => 'Casado(a)',
                    ])
                    ->default(null),
                Select::make('grau_academico')
                    ->options([
                        ' Técnico(a) Médio(a)' => ' Técnico(a) Médio(a)',
                        'Licenciado(a)' => 'Licenciado(a)',
                        'Mestre' => 'Mestre',
                        'Doutor(a)' => 'Doutor(a)',
                ])
                ->required(),
                TextInput::make('curso')
                    ->required()
                    ->maxLength(255),
                TextInput::make('ano_conclusao')
                    ->required()
                    ->maxLength(255),
                Select::make('categoria_id')
                    ->label('Categoria')
                    ->options(Categoria::pluck('nome', 'id')) 
                    ->live()
                ->required(),
                TextInput::make('funcao')
                    ->maxLength(255)
                    ->default(null),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255)
                    ->default(null),
                TextInput::make('telefone')
                    ->tel()
                    ->maxLength(255)
                    ->default(null),
                TextInput::make('bi')
                    ->maxLength(255)
                    ->default(null),
                    

                // Campo para selecionar múltiplas disciplinas
                MultiSelect::make('disciplinas')
                    ->relationship('disciplinas', 'nome') // Usa o relacionamento Many-to-Many
                    ->preload(),
                Repeater::make('documentos')
                    ->label('Documentos')
                    ->schema([
                        Grid::make(2)->schema([ // Cria uma grade com duas colunas
                            TextInput::make('tipo')
                                ->label('Tipo')
                                ->required()
                                ->columnSpan(1), // Ocupa metade da largura

                            FileUpload::make('documento_pdf')
                                ->label('Documento')
                                ->columnSpan(1), // Ocupa metade da largura
                        ]),
                    ])
                    ->addable(true)
                    ->deletable(true)
                    ->default([])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('num_agente')
                    ->label('Nº de Agente')
                    ->searchable(),
                TextColumn::make('categoria.nome')
                    ->label('Categoria')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('funcao')
                    ->label('Função')
                    ->searchable(),
                TextColumn::make('genero')
                    ->label('Género')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('data_nascimento')
                    ->label('Data de Nascimento')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('naturalidade')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('morada')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('estado_civil')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('grau_academico')
                    ->label('Grau Académico')
                    ->searchable(),
                TextColumn::make('curso')
                    ->searchable(),
                TextColumn::make('ano_conclusao')
                    ->label('Ano de Conclusão')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('telefone')
                    ->searchable(),
                TextColumn::make('bi')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Data de Criação')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Data de Atualização')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Editar')
                    ->icon('heroicon-o-pencil')
                    ->color('primary'),
                Tables\Actions\DeleteAction::make()
                    ->label('Eliminar')
                    ->icon('heroicon-o-trash')
                    ->color('danger'),
                Tables\Actions\ViewAction::make()
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->color('info'), 
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
