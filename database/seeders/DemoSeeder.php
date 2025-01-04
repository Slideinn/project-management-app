<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Users
        $admin = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
        ]);

        User::factory(10)->create();

        // Projects
        Project::factory(3)->withCreator($admin->id)->create();

        // Tasks
        $projects = Project::all();
        $users = User::all();
        foreach ($projects as $project) {
            $project->tasks()->saveMany(
                Task::factory(5)->withCreator($project->creator_id)->make()
            );

            $project->tasks->each(function ($task) use ($users) {
                $task->assignees()->attach($users->random(3));
                $task->watchers()->attach($users->random(3));
            });
        }
    }
}
