<?php

namespace Database\Factories;

use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name().fake()->numberBetween(0,100),
            'description' => fake()->text(),
            'start_date' => fake()->dateTime(),
            'end_date' => fake()->dateTime(),
            'status' => 1
        ];
    }
}
