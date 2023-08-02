<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payroll>
 */
class PayrollFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "currency" => "EGP",
            "base" => $this->faker->randomFloat(2, 1000, 10000),
            "total_deductions" => $this->faker->randomFloat(2, 1000, 10000),
            "total_additions" => $this->faker->randomFloat(2, 1000, 10000),
            "performance_multiplier" => $this->faker->randomFloat(2, 0, 1),
            "total_payable" => $this->faker->randomFloat(2, 1000, 10000),
            "due_date" => Carbon::createFromTimestamp(mt_rand(Carbon::now()->subYears(3)->timestamp, Carbon::now()->timestamp))->format('Y-m-d'),
            "is_reviewed" => false,
            "status" => false,
        ];
    }
}
