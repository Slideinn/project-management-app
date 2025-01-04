<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use App\Notifications\Filament\UserTaskAssignationAdded;
use App\Notifications\Filament\UserTaskWatcherAdded;
use App\Traits\HasCreatorId;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateTask extends CreateRecord
{
    use HasCreatorId;

    protected static string $resource = TaskResource::class;

    protected function afterCreate(): void
    {
        $this->record->assignees->each(fn($assignee) => $assignee->notify(new UserTaskAssignationAdded($this->record)));
        $this->record->watchers->each(fn($watcher) => $watcher->notify(new UserTaskWatcherAdded($this->record)));
    }
}
