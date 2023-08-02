<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Metric>
 */
class MetricFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'criteria' => $this->faker->sentence(8),
            'weight' => $this->faker->numberBetween(1, 2),
            'step' => $this->faker->randomFloat(2, 0.1, 0.1),
        ];
    }
}
