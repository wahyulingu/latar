<?php

namespace Database\Factories\Product;

use App\Models\Product\ProductMaster;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'master_id' => ProductMaster::factory(),
            'name' => $this->faker->words(4, true),
            'price' => 200000,
            'description' => $this->faker->words(20, true),
        ];
    }
}
