<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_lists_categories_with_post_counts(): void
    {
        $category = Category::factory()->has(Post::factory()->count(2))->create();

        $this->get(route('categories.index'))
            ->assertOk()
            ->assertSee($category->name)
            ->assertSee('2 posts');
    }

    public function test_category_can_be_created(): void
    {
        $this->post(route('categories.store'), [
            'name' => 'Web Development',
            'description' => 'All about the web',
        ])->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', ['name' => 'Web Development', 'slug' => 'web-development']);
    }

    public function test_category_name_must_be_unique(): void
    {
        Category::factory()->create(['name' => 'News']);

        $this->post(route('categories.store'), ['name' => 'News'])
            ->assertSessionHasErrors('name');
    }

    public function test_show_redirects_to_filtered_posts(): void
    {
        $category = Category::factory()->create();

        $this->get(route('categories.show', $category))
            ->assertRedirect(route('posts.index', ['category' => $category->slug]));
    }

    public function test_category_can_be_renamed(): void
    {
        $category = Category::factory()->create(['name' => 'Old']);

        $this->put(route('categories.update', $category), ['name' => 'New Name'])
            ->assertRedirect(route('categories.index'));

        $category->refresh();
        $this->assertSame('New Name', $category->name);
        $this->assertSame('new-name', $category->slug);
    }

    public function test_deleting_category_keeps_its_posts_uncategorised(): void
    {
        $post = Post::factory()->create();
        $category = $post->category;

        $this->delete(route('categories.destroy', $category))->assertRedirect(route('categories.index'));

        $this->assertModelMissing($category);
        $this->assertNull($post->fresh()->category_id);
    }
}
