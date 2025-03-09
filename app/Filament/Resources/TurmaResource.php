<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TurmaResource\Pages;
use App\Filament\Resources\TurmaResource\RelationManagers;
use App\Models\AnoLetivo;
use App\Models\Classe;
use App\Models\Disciplina;
use App\Models\Periodo;
use App\Models\Professor;
use App\Models\Turma;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TurmaResource extends Resource
{
    protected static ?string $model = Turma::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Dados da Turma')
                    ->schema([
                        Select::make('ano_letivo_id')
                            ->label('Ano Letivo')
                            ->options(AnoLetivo::pluck('ano_letivo', 'id'))
                            ->live()
                            ->default(fn() => AnoLetivo::latest('id')->value('id'))
                            ->required()
                            ->columnSpan(2),

                        Select::make('classe_id')
                            ->label('Classe')
                            ->options(
                                fn($get) =>
                                Classe::where('ano_letivo_id', $get('ano_letivo_id'))->pluck('nome', 'id')
                            )
                            ->live()
                            ->required()
                            ->columnSpan(2),

                        Select::make('periodo_id')
                            ->label('Período')
                            ->options(Periodo::pluck('nome', 'id'))
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('nome')
                            ->label('Nome da Turma')
                            ->required()
                            ->maxLength(255)
                            ->unique(
                                table: 'turmas',
                                column: 'nome',
                                ignoreRecord: true,
                                modifyRuleUsing: fn($rule, $get) =>
                                $rule->where('classe_id', $get('classe_id'))
                            )
                            ->validationMessages([
                                'unique' => 'Já existe uma turma com este nome na classe selecionada.',
                            ])
                            ->columnSpan(2),

                        TextInput::make('lotacao')
                            ->label('Lotação Máxima')
                            ->required()
                            ->numeric()
                            ->columnSpan(2),

                        Textarea::make('descricao')
                            ->label('Descrição da Turma')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(4),

                    Section::make('Disciplinas e Professores')
                    ->description('Todas as disciplinas cadastradas aparecerão aqui. Selecione o professor responsável para cada uma.')
                    ->schema([
                        Repeater::make('disciplinasProfessores')
                            ->label('Professores por Disciplina')
                            ->relationship('disciplinasProfessores')
                            ->schema([
                                Select::make('disciplina_id') // 🔥 Apenas para exibição
                    ->label('Disciplina')
                    ->options(Disciplina::pluck('nome', 'id'))
                    ->default(fn ($get) => $get('disciplina_id'))
                    ->disabled()
                    ->columnSpan(1),

                                Hidden::make('disciplina_id') // 🔥 Agora garantindo que a disciplina seja salva
                                    ->dehydrated()
                                    ->default(fn ($get) => $get('disciplina_id'))
                                    ->afterStateUpdated(fn ($set, $state) => $set('disciplina_id', $state)),

                                Select::make('professor_id')
                                    ->label('Professor')
                                    ->options(fn ($get) =>
                                        $get('disciplina_id')
                                            ? Professor::whereHas('disciplinas', fn ($query) =>
                                                $query->where('disciplinas.id', $get('disciplina_id'))
                                            )->pluck('nome', 'id')
                                            : []
                                    )
                                    ->searchable()
                                    ->columnSpan(1)
                                    ->default(fn ($get, $state) => $state)
                                    ->helperText(fn ($get) =>
                                        Professor::whereHas('disciplinas', fn ($query) =>
                                            $query->where('disciplinas.id', $get('disciplina_id'))
                                        )->exists()
                                        ? ''
                                        : '❌ Disciplina sem professor disponível.'
                                    )
                                    ->extraAttributes(['class' => 'text-red-600 font-bold']),
                            ])
                            ->columns(2)
                            ->grid(2)
                            ->columnSpanFull()
                            ->default(fn ($record) =>
                                $record
                                    ? $record->disciplinasProfessores->map(fn ($relacao) => [
                                        'disciplina_id' => $relacao->disciplina_id,
                                        'disciplina_nome' => $relacao->disciplina->nome, // Recupera nome corretamente
                                        'professor_id' => $relacao->professor_id,
                                    ])->toArray()
                                    : Disciplina::all()->map(fn ($disciplina) => [
                                        'disciplina_id' => $disciplina->id,
                                        'disciplina_nome' => $disciplina->nome,
                                    ])->toArray()
                            )
                            ->addable(fn ($get) => count($get('disciplinasProfessores') ?? []) < Disciplina::count())
                            ->deletable(true),
                    ]),

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
                    ->default(fn() => AnoLetivo::latest('id')->value('id')) // Define o último ano letivo como padrão
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
