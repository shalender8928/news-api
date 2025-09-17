<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Article;
use App\Models\Source;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'external_id'   => Str::uuid(),
            'source_id'     => Source::first()->id,   
            'author_id'     => Author::factory(),     
            'category_id'   => Category::factory(), 
            'title'         => $this->faker->sentence(),
            'description'   => $this->faker->paragraph(),
            'content'       => $this->faker->text(500),
            'url'           => $this->faker->url(),
            'url_to_image'  => $this->faker->imageUrl(),
            'published_at'  => $this->faker->dateTimeBetween('-1 year', 'now'),
            'raw'           => json_encode(['sample' => 'data']),
        ];
    }
}
