<?php

namespace App\Filament\Matricula\Resources;

use App\Filament\Resources\MatriculaResource\Pages;
use App\Models\AnoLetivo;
use App\Models\Classe;
use App\Models\Matricula;
use App\Models\VagaClasse;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MatriculaResource extends Resource
{
    protected static ?string $model = Matricula::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nome')
                    ->label('Nome do Aluno')
                    ->required()
                    ->maxLength(255),

                DatePicker::make('data_nascimento')
                    ->label('Data de Nascimento')
                    ->nullable(),

                TextInput::make('naturalidade')
                    ->label('Naturalidade')
                    ->nullable(),

                TextInput::make('pai')
                    ->label('Nome do Pai')
                    ->nullable(),

                TextInput::make('mae')
                    ->label('Nome da Mãe')
                    ->nullable(),

                Select::make('ano_letivo_id')
                    ->label('Ano Letivo')
                    ->options(AnoLetivo::pluck('ano_letivo', 'id')) // Garante que todas as opções estejam disponíveis
                    ->live()
                    ->default(fn() => AnoLetivo::latest('id')->value('id')) // Apenas seleciona o último ano letivo como padrão
                    ->required(),

                Select::make('classe_id')
                    ->label('Classe')
                    ->options(
                        fn($get) =>
                        Classe::where('ano_letivo_id', $get('ano_letivo_id'))
                            ->pluck('nome', 'id')
                    )
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $anoLetivo = $get('ano_letivo_id');
                        $classeId = $state;

                        if (!$anoLetivo || !$classeId)
                            return;

                        $vaga = VagaClasse::where('classe_id', $classeId)
                            ->where('ano_letivo_id', $anoLetivo)
                            ->first();

                        if (!$vaga) {
                            Notification::make()
                                ->title('Vagas não definidas')
                                ->body('⚠️ Não foram definidas vagas para classe selecionada.')
                                ->danger()
                                ->send();

                            $set('classe_id', null);
                            $set('forcar_atualizacao', now()); // Dispara atualização do formulário
                            return;
                        }

                        if ($vaga->estado !== 'Aberta') {
                            Notification::make()
                                ->title('Matrículas fechadas')
                                ->body('⚠️ As matrículas para a classe selecionada estão fechadas.')
                                ->danger()
                                ->send();

                            $set('classe_id', null);
                            //$set('forcar_atualizacao', now());
                            return;
                        }

                        $matriculados = Matricula::where('classe_id', $classeId)
                            ->where('ano_letivo_id', $anoLetivo)
                            ->count();

                        if ($matriculados >= $vaga->quantidade) {
                            Notification::make()
                                ->title('Erro')
                                ->body('⚠️ Não há mais vagas disponíveis para esta classe.')
                                ->danger()
                                ->send();

                            $set('classe_id', null);
                        }
                    }),

                DatePicker::make('data_matricula')
                    ->label('Data da Matrícula')
                    ->default(now())
                    ->required(),

                FileUpload::make('foto')
                    ->label('Foto do Aluno')
                    ->nullable(),

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
                    ->columnSpanFull(), // Ocupa toda a largura do formulário

                Forms\Components\Textarea::make('observacoes')
                    ->label('Observações')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->label('Nome do Aluno')
                    ->searchable(),

                Tables\Columns\TextColumn::make('anoLetivo.ano_letivo')
                    ->label('Ano Letivo')
                    ->sortable(),

                Tables\Columns\TextColumn::make('classe.nome')
                    ->label('Classe')
                    ->sortable(),

                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado da Matrícula')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('data_matricula')
                    ->label('Data da Matrícula')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('ano_letivo_id')
                    ->label('Filtrar por Ano Letivo')
                    ->options(AnoLetivo::pluck('ano_letivo', 'id'))
                    ->query(
                        fn(Builder $query, array $data) =>
                        $data['value'] ? $query->where('ano_letivo_id', $data['value']) : $query
                    ),

                SelectFilter::make('classe_id')
                    ->label('Filtrar por Classe')
                    ->options(Classe::pluck('nome', 'id'))
                    ->query(
                        fn(Builder $query, array $data) =>
                        $data['value'] ? $query->where('classe_id', $data['value']) : $query
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('ano_letivo_id', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // Aqui podem ser adicionadas relações, como o responsável, se necessário
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
