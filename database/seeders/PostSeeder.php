<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::all()->each(function (Category $category): void {
            Post::factory()->count(3)->for($category)->create();
        });

        Post::factory()->draft()->create(['category_id' => null]);
    }
}
