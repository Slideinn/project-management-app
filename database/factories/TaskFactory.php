<?php

namespace Database\Factories;

use App\Enums\TaskStatusEnum;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'creator_id' => User::first()->id,
            'project_id' => Project::first()->id,
            'start_date' => $this->faker->dateTimeBetween('-1 week', '+1 week'),
            'end_date' => $this->faker->dateTimeBetween('+1 week', '+2 week'),
            'status' => $this->faker->randomElement(array_column(TaskStatusEnum::cases(), 'value'))
        ];
    }

    public function withCreator($creatorId): static
    {
        return $this->state(fn(array $attributes) => [
            'creator_id' => $creatorId,
        ]);
    }

    public function withProject($projectId): static
    {
        return $this->state(fn(array $attributes) => [
            'project_id' => $projectId,
        ]);
    }
}
