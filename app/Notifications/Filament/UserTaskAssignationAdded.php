<?php

namespace App\Notifications\Filament;

use App\Filament\Resources\TaskResource;
use App\Models\Task;
use Filament\Notifications\Actions\Action;

class UserTaskAssignationAdded extends DatabaseNotification
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
        return 'Task Assigned';
    }

    public function body(): string
    {
        return "You have been assigned to the task: {$this->task->name}";
    }

    public function icon(): string
    {
        return 'heroicon-o-check-circle';
    }

    public function actions(): array
    {
        return [
            Action::make('View')
                ->url(TaskResource::getUrl('edit', ['record' => $this->task])),
        ];
    }


}
