<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'title' => fake()->sentence(),
            'slug' => fn (array $attributes) => Str::slug($attributes['title']).'-'.Str::lower(Str::random(6)),
            'excerpt' => fake()->sentence(15),
            'body' => implode("\n\n", fake()->paragraphs(4)),
            'published_at' => fake()->dateTimeBetween('-1 month'),
        ];
    }

    /**
     * Indicate that the post is an unpublished draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => null,
        ]);
    }
}
