<?php

namespace App\Filament\Resources\TaskResource\RelationManagers;

use App\Filament\Resources\TaskResource;
use App\Models\User;
use App\Notifications\Filament\UserTaskAssignationAdded;
use App\Notifications\Filament\UserTaskAssignationRemoved;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssigneesRelationManager extends RelationManager
{
    protected static string $relationship = 'assignees';

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
                ->after(function() {
                    $this->notifyUsers('attach');
                }),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()->after(function() {
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

        $assignee = $action === 'attach' ? User::find( $this->mountedTableActionsData[0]['recordId']) : $this->getMountedTableActionRecord();

        if (!$assignee) {
            return;
        }

        $task = $this->getOwnerRecord();

        switch ($action) {
            case 'attach':
                $assignee->notify(new UserTaskAssignationAdded($task));
                break;
            case 'detach':
                $assignee->notify(new UserTaskAssignationRemoved($task));
                break;
        }
    }
}
