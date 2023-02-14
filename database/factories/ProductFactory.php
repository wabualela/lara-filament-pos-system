<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'quantity' => $this->faker->numberBetween(10,1000),
            'price' => $this->faker->numberBetween(1000,100000),
            'note' => $this->faker->realText(),
            'product_categories_id' => ProductCategory::factory(),
            'units_id' => Unit::factory(),
        ];
    }
}
