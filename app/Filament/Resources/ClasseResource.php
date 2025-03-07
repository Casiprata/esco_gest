<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClasseResource\Pages;
use App\Filament\Resources\ClasseResource\RelationManagers;
use App\Models\AnoLetivo;
use App\Models\Classe;
use App\Models\Ensino;
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

class ClasseResource extends Resource
{
    protected static ?string $model = Classe::class;

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

                TextInput::make('nome')
                    ->required()
                    ->maxLength(255)
                    ->unique(
                        table: Classe::class,
                        column: 'nome',
                        ignoreRecord: true, // Permite editar sem erro
                        modifyRuleUsing: function ($rule, $get) {
                            return $rule->where('ano_letivo_id', $get('ano_letivo_id'));
                        }
                    )
                    ->validationMessages([
                    'unique' => 'Já existe uma classe com este nome no ano letivo selecionado.',
                    ]),
                Select::make('ensino_id')
                    ->label('Ensino')
                    ->options(Ensino::all()->pluck('nome', 'id'))
                    ->live()
                    ->required(),

                Textarea::make('descricao')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('nome')
                ->label('Nome da Classe')
                ->searchable(),
            Tables\Columns\TextColumn::make('ensino.nome')
                ->label('Ensino')
                ->sortable(),
            Tables\Columns\TextColumn::make('anoLetivo.ano_letivo')
                ->label('Ano Letivo')
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            
                SelectFilter::make('ano_letivo_id')
                    ->label('Filtrar por Ano Letivo')
                    ->options(AnoLetivo::pluck('ano_letivo', 'id'))
                    ->query(function (Builder $query, array $data) {
                        if (!isset($data['value'])) {
                            return $query; // Se nenhum valor for selecionado, retorna sem filtro
                        }

                        return $query->where('ano_letivo_id', $data['value']);
                    }),
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
            'index' => Pages\ListClasses::route('/'),
            'create' => Pages\CreateClasse::route('/create'),
            'edit' => Pages\EditClasse::route('/{record}/edit'),
        ];
    }
}
