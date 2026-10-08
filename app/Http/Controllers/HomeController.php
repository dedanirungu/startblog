<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the blog home page with the latest published posts.
     */
    public function __invoke(): View
    {
        $latestPosts = Post::published()
            ->with('category')
            ->latest('published_at')
            ->take(7)
            ->get();

        return view('home', [
            'featuredPost' => $latestPosts->first(),
            'recentPosts' => $latestPosts->skip(1),
            'categories' => Category::withCount(['posts' => fn ($query) => $query->published()])
                ->orderBy('name')
                ->get(),
        ]);
    }
}
