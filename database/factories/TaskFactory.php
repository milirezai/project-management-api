<?php

namespace Database\Factories;

use App\Models\Project\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->title(),
            'description' => fake()->text(300),
            'start_date' => fake()->dateTime(),
            'end_date' => fake()->dateTime(),
            'status' => 1
        ];
    }
}
