<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Addition>
 */
class AdditionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
//        return [
//            'rewards' => 0,
//            'incentives' => 0,
//            'reimbursements' => 0,
//            'shift_differentials' => 0,
//            'commissions' => 0,
//            'due_date' => Carbon::now()->toDateString(),
//            'status' => false,
//            "overtime" => 0,
//        ];
        return [
            'rewards' => $this->faker->randomFloat(2, 0, 1000),
            'incentives' => $this->faker->randomFloat(2, 0, 1000),
            'reimbursements' => $this->faker->randomFloat(2, 0, 1000),
            'shift_differentials' => $this->faker->randomFloat(2, 0, 1000),
            'commissions' => $this->faker->randomFloat(2, 0, 1000),
            'due_date' => $this->faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),
            'status' => $this->faker->boolean,
            "overtime" => 0,
        ];
    }
}
