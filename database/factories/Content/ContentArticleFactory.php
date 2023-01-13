<?php

namespace Database\Factories\Content;

use App\Models\Content\ContentCategory;
use App\Models\Profile\ProfileAuthor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content\ContentArticle>
 */
class ContentArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'author_id' => ProfileAuthor::factory(),
            'category_id' => ContentCategory::factory(),
            'title' => $this->faker->words(12, true),
            'content' => $this->faker->paragraph(6),
            'description' => $this->faker->words(20, true),
        ];
    }
}
