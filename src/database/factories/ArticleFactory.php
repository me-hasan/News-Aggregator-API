<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'summary' => $this->faker->paragraph,
            'content' => $this->faker->paragraphs(3, true),
            'url' => $this->faker->unique()->url,
            'image_url' => $this->faker->imageUrl(),
            'source' => $this->faker->randomElement(['BBC', 'NYT', 'Guardian']),
            'author' => $this->faker->name,
            'category' => $this->faker->word,
            'published_at' => $this->faker->dateTime,
            'api_origin' => $this->faker->randomElement(['newsapi', 'guardian', 'nyt']),
        ];
    }
}
