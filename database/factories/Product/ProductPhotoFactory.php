<?php

namespace Database\Factories\Product;

use App\Models\Product\ProductMaster;
use App\Models\Product\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product\ProductPhoto>
 */
class ProductPhotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'product_id' => ProductMaster::factory(),
            'product_type' => ProductMaster::class,
            'name' => ucfirst($this->faker->words(2, true)),
            'description' => ucfirst($this->faker->words(20, true)),
        ];
    }

    public function forVariant()
    {
        return $this->state([
            'product_id' => ProductVariant::factory(),
            'product_type' => ProductVariant::class,
        ]);
    }
}
