<?php

namespace App\Notifications\Filament;

use App\Filament\Resources\TaskResource;
use App\Models\Task;
use Filament\Notifications\Actions\Action;

class UserTaskAssignationRemoved extends DatabaseNotification
{

    /**
     * Create a new notification instance.
     * 
     * @param Task $task
     */
    public function __construct(public Task $task)
    {
        parent::__construct();
    }

    public function title(): string
    {
        return 'Removed from task';
    }

    public function body(): string
    {
        return "You have been removed from the task: {$this->task->name}";
    }

    public function icon(): string
    {
        return 'heroicon-o-x-circle';
    }

    public function actions(): array
    {
        return [
            Action::make('View')
                ->url(TaskResource::getUrl('edit', ['record' => $this->task])),
        ];
    }


}
