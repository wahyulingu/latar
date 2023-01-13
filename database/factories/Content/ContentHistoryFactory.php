<?php

namespace Database\Factories\Content;

use App\Models\Content\ContentCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content\ContentHistory>
 */
class ContentHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'master_id' => ContentArticleFactory::factory(),
            'category_id' => ContentCategory::factory(),
            'title' => $this->faker->words(12, true),
            'content' => $this->faker->paragraph(6),
            'description' => $this->faker->words(20, true),
        ];
    }
}
