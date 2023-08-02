<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deduction>
 */
class DeductionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "income_tax" => $this->faker->randomFloat(2, 0, 1000),
            "social_security_contributions" => $this->faker->randomFloat(2, 0, 1000),
            "health_insurance" => $this->faker->randomFloat(2, 0, 1000),
            "retirement_plan" => $this->faker->randomFloat(2, 0, 1000),
            "benefits" => $this->faker->randomFloat(2, 0, 1000),
            "union_fees" => $this->faker->randomFloat(2, 0, 1000),
            "due_date" => $this->faker->dateTimeBetween("-1 years", "now")->format("Y-m-d"),
            "status" => $this->faker->boolean,
            "undertime" => 0,
        ];
    }
}
