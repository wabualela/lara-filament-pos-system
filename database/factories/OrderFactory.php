<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'client_id' => Client::factory(),
            'sub_total' => $this->faker->numberBetween(100, 1000),
            'vat' => $this->faker->numberBetween(10, 100),
            'total' => $this->faker->numberBetween(1000, 10000),
            'quantity' => $this->faker->numberBetween(1, 1000),
            'pay' => $this->faker->numberBetween(1000, 10000),
            'due' => $this->faker->namnumberBetween(1000, 10000),
            'paid_by' => $this->faker->name,
            'order_date' => $this->faker->date(),
            'order_month' => $this->faker->month(),
            'order_year' => $this->faker->year(),
        ];
    }
}
