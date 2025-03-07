<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TurmaResource\Pages;
use App\Filament\Resources\TurmaResource\RelationManagers;
use App\Models\AnoLetivo;
use App\Models\Classe;
use App\Models\Periodo;
use App\Models\Turma;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TurmaResource extends Resource
{
    protected static ?string $model = Turma::class;

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
                ->label('Classe')
                ->options(fn ($get) =>
                    Classe::where('ano_letivo_id', $get('ano_letivo_id'))->pluck('nome', 'id')
                ) // Filtra as classes com base no ano letivo selecionado
                ->live()
                ->required(),

            Select::make('periodo_id')
                ->label('Período')
                ->options(Periodo::pluck('nome', 'id'))
                ->required(),

            TextInput::make('nome')
                ->label('Nome da Turma')
                ->required()
                ->maxLength(255)
                ->unique(
                    table: 'turmas',
                    column: 'nome',
                    ignoreRecord: true, // Permite edição sem erro
                    modifyRuleUsing: function ($rule, $get) {
                        return $rule->where('classe_id', $get('classe_id'));
                    }
                )
                ->validationMessages([
                    'unique' => 'Já existe uma turma com este nome na classe selecionada.',
                ]),

            TextInput::make('lotacao')
                ->required()
                ->numeric(),

            Textarea::make('descricao')
                ->required()
                ->columnSpanFull(),
        ]);
}


public static function table(Table $table): Table
{
    return $table
        ->columns([


            Tables\Columns\TextColumn::make('classe.nome')
                ->label('Classe')
                ->sortable(),
            Tables\Columns\TextColumn::make('nome')
                ->label('Nome da Turma')
                ->searchable(),

            Tables\Columns\TextColumn::make('lotacao')
                ->numeric()
                ->sortable(),

            Tables\Columns\TextColumn::make('classe.anoLetivo.ano_letivo')
                ->label('Ano Letivo')
                ->sortable(),

            Tables\Columns\TextColumn::make('periodo.nome')
                ->label('Período')
                ->sortable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Criado em')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('updated_at')
                ->label('Atualizado em')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            SelectFilter::make('ano_letivo_id')
                ->label('Filtrar por Ano Letivo')
                ->options(AnoLetivo::pluck('ano_letivo', 'id'))
                ->default(fn () => AnoLetivo::latest('id')->value('id')) // Define o último ano letivo como padrão
                ->query(function (Builder $query, array $data) {
                    if (!isset($data['value'])) {
                        return $query; // Se nenhum valor for selecionado, retorna sem filtro
                    }

                    return $query->whereHas('classe', function ($q) use ($data) {
                        $q->where('ano_letivo_id', $data['value']);
                    });
                }),
        ])
        ->defaultSort('classe.nome')
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListTurmas::route('/'),
            'create' => Pages\CreateTurma::route('/create'),
            'edit' => Pages\EditTurma::route('/{record}/edit'),
        ];
    }
}
