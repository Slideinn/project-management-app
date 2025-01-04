<?php

namespace App\Filament\Resources\TaskResource\RelationManagers;

use App\Models\User;
use App\Notifications\Filament\UserTaskWatcherAdded;
use App\Notifications\Filament\UserTaskWatcherRemoved;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WatchersRelationManager extends RelationManager
{
    protected static string $relationship = 'watchers';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->after(function () {
                        $this->notifyUsers('attach');
                    }),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()->after(function () {
                    $this->notifyUsers('detach');
                }),
            ])
            ->bulkActions([
            ]);
    }

    public function notifyUsers($action): void
    {

        if (!in_array($action, ['attach', 'detach'])) {
            return;
        }

        $assignee = $action === 'attach' ? User::find($this->mountedTableActionsData[0]['recordId']) : $this->getMountedTableActionRecord();

        if (!$assignee) {
            return;
        }

        $task = $this->getOwnerRecord();

        switch ($action) {
            case 'attach':
                $assignee->notify(new UserTaskWatcherAdded($task));
                break;
            case 'detach':
                $assignee->notify(new UserTaskWatcherRemoved($task));
                break;
        }
    }
}
