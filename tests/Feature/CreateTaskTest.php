<?php

namespace Tests\Feature;

use App\Enums\TaskStatusEnum;
use App\Filament\Resources\TaskResource\Pages\CreateTask;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_create_task(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->withCreator($user->id)->create();

        $data = [
            'name' => 'Task Name',
            'description' => 'Task Description',
            'start_date' => now()->addDays(1)->toDateTimeString(),
            'end_date' => now()->addDays(2)->toDateTimeString(),
            'status' => TaskStatusEnum::IN_PROGRESS->value,
            'project_id' => $project->id,
        ];

        $task = Task::factory()->make($data);

        Livewire::test(CreateTask::class)
            ->fillForm([
                'name' => $task->name,
                'description' => $task->description,
                'start_date' => $task->start_date,
                'end_date' => $task->end_date,
                'status' => $task->status,
                'project_id' => $project->id,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('tasks', $data);
    }

    public function test_can_attach_assignees_to_task(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->withCreator($user->id)->create();

        $data = [
            'name' => 'Task Name',
            'description' => 'Task Description',
            'start_date' => now()->addDays(1)->toDateTimeString(),
            'end_date' => now()->addDays(2)->toDateTimeString(),
            'status' => TaskStatusEnum::IN_PROGRESS->value,
            'project_id' => $project->id,
        ];

        $task = Task::factory()->make($data);

        $assignees = User::factory(3)->create();

        Livewire::test(CreateTask::class)
            ->fillForm([
                'name' => $task->name,
                'description' => $task->description,
                'start_date' => $task->start_date,
                'end_date' => $task->end_date,
                'status' => $task->status,
                'project_id' => $project->id,
                'assignees' => $assignees->pluck('id')->toArray(),
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('tasks', $data);

        $this->assertDatabaseCount('task_assignees', 3);
    }
}
