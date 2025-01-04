<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource\RelationManagers\AssigneesRelationManager;
use App\Filament\Resources\TaskResource\RelationManagers\WatchersRelationManager;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;

use App\Enums\TaskStatusEnum;
use App\Models\Task;

use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;


class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            RichEditor::make('description')
                ->maxLength(65535)
                ->required(),
            DateTimePicker::make('start_date')
                ->required(),
            DateTimePicker::make('end_date'),
            Select::make('status')
                ->options(TaskStatusEnum::toSelectArray())
                ->required(),
            BelongsToSelect::make('project_id')
                ->relationship('project', 'name')
                ->required(),
            MultiSelect::make('assignees')
                ->relationship('assignees', 'name')
                ->visible(fn ($livewire): bool => $livewire instanceof CreateRecord),
            MultiSelect::make('watchers')
                ->relationship('watchers', 'name')
                ->visible(fn ($livewire): bool => $livewire instanceof CreateRecord),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => TaskStatusEnum::toSelectArray()[$state->value]),
                TextColumn::make('project.name')
                    ->label('Project')
                    ->sortable(),
                TextColumn::make('start_date')
                    ->sortable(),
                TextColumn::make('end_date')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(TaskStatusEnum::toSelectArray())
                    ->label('Status'),
                Filter::make('start_date')
                    ->form([
                        DateTimePicker::make('start_date_from'),
                        DateTimePicker::make('start_date_to'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['start_date_from'], fn(Builder $query, $date) => $query->whereDate('start_date', '>=', $date))
                            ->when($data['start_date_to'], fn(Builder $query, $date) => $query->whereDate('start_date', '<=', $date));
                    }),
                Filter::make('end_date')
                    ->form([
                        DateTimePicker::make('end_date_from'),
                        DateTimePicker::make('end_date_to'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['end_date_from'], fn(Builder $query, $date) => $query->whereDate('end_date', '>=', $date))
                            ->when($data['end_date_to'], fn(Builder $query, $date) => $query->whereDate('end_date', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            AssigneesRelationManager::class,
            WatchersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
            'view' => Pages\ViewTask::route('/{record}'),
        ];
    }
}
