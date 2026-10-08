<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_shows_published_posts_categories_and_write_link(): void
    {
        $published = Post::factory()->create(['title' => 'Published story']);
        $draft = Post::factory()->draft()->create(['title' => 'Secret draft']);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee($published->title)
            ->assertSee($published->category->name)
            ->assertDontSee($draft->title)
            ->assertSee(route('posts.create'));
    }

    public function test_home_prompts_to_write_first_post_when_empty(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Write the first post');
    }
}
