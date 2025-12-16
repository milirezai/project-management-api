<?php

namespace Database\Factories;

use App\Models\Collaboration\File;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FileFactory extends Factory
{
    protected $model = File::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fileable_type' => 1,
            'path' => fake()->filePath(),
            'type' => fake()->fileExtension(),
            'size' => fake()->numberBetween(0,2000)
        ];
    }
}
