<?php

namespace Database\Factories\Product;

use App\Models\Product\ProductCategory;
use App\Models\Profile\ProfileOwner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product\ProductMaster>
 */
class ProductMasterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'owner_id' => ProfileOwner::factory(),
            'category_id' => ProductCategory::factory(),
            'name' => $this->faker->words(4, true),
            'description' => $this->faker->words(20, true),
        ];
    }
}
