<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_lists_posts_and_filters_by_category(): void
    {
        $news = Category::factory()->create(['name' => 'News']);
        $newsPost = Post::factory()->for($news)->create(['title' => 'Breaking story']);
        $otherPost = Post::factory()->create(['title' => 'Unrelated story']);

        $this->get(route('posts.index'))
            ->assertOk()
            ->assertSee($newsPost->title)
            ->assertSee($otherPost->title);

        $this->get(route('posts.index', ['category' => $news->slug]))
            ->assertOk()
            ->assertSee($newsPost->title)
            ->assertDontSee($otherPost->title);
    }

    public function test_index_returns_not_found_for_unknown_category(): void
    {
        $this->get(route('posts.index', ['category' => 'missing']))->assertNotFound();
    }

    public function test_create_form_lists_categories(): void
    {
        $category = Category::factory()->create();

        $this->get(route('posts.create'))
            ->assertOk()
            ->assertSee($category->name);
    }

    public function test_post_can_be_created_in_a_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->post(route('posts.store'), [
            'title' => 'Hello World',
            'category_id' => $category->id,
            'excerpt' => 'Short intro',
            'body' => 'Post body',
            'publish' => '1',
        ]);

        $post = Post::sole();
        $response->assertRedirect(route('posts.show', $post));
        $this->assertSame('hello-world', $post->slug);
        $this->assertTrue($post->category->is($category));
        $this->assertTrue($post->isPublished());
    }

    public function test_post_can_be_saved_as_uncategorised_draft(): void
    {
        $this->post(route('posts.store'), [
            'title' => 'Draft',
            'body' => 'Body',
            'publish' => '0',
        ])->assertRedirect();

        $post = Post::sole();
        $this->assertNull($post->category_id);
        $this->assertNull($post->published_at);
    }

    public function test_duplicate_titles_get_unique_slugs(): void
    {
        Post::factory()->create(['slug' => 'same-title']);

        $this->post(route('posts.store'), ['title' => 'Same Title', 'body' => 'Body']);

        $this->assertDatabaseHas('posts', ['slug' => 'same-title-2']);
    }

    public function test_store_validates_input(): void
    {
        $this->post(route('posts.store'), ['category_id' => 999])
            ->assertSessionHasErrors(['title', 'body', 'category_id']);

        $this->assertDatabaseCount('posts', 0);
    }

    public function test_show_displays_post_with_category(): void
    {
        $post = Post::factory()->create();

        $this->get(route('posts.show', $post))
            ->assertOk()
            ->assertSee($post->title)
            ->assertSee($post->category->name);
    }

    public function test_post_can_be_updated(): void
    {
        $post = Post::factory()->draft()->create();
        $newCategory = Category::factory()->create();

        $this->put(route('posts.update', $post), [
            'title' => 'Updated title',
            'category_id' => $newCategory->id,
            'body' => 'Updated body',
            'publish' => '1',
        ])->assertRedirect(route('posts.show', $post));

        $post->refresh();
        $this->assertSame('Updated title', $post->title);
        $this->assertTrue($post->category->is($newCategory));
        $this->assertTrue($post->isPublished());
    }

    public function test_post_can_be_deleted(): void
    {
        $post = Post::factory()->create();

        $this->delete(route('posts.destroy', $post))->assertRedirect(route('posts.index'));

        $this->assertModelMissing($post);
    }
}
