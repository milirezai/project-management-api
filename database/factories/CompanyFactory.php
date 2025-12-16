<?php

namespace Database\Factories;

use App\Models\Collaboration\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'description' => fake()->text(300),
            'address' => fake()->address(),
            'phone_number' => fake()->phoneNumber(),
            'email' => fake()->companyEmail(),
            'website' => fake()->url()
        ];
    }
}
