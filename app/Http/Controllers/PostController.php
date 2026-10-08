<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        if ($request->filled('category')) {
            $selectedCategory = Category::where('slug', $request->string('category'))->firstOrFail();
        } else {
            $selectedCategory = null;
        }

        $posts = Post::query()
            ->with('category')
            ->when($selectedCategory, fn ($query) => $query->whereBelongsTo($selectedCategory))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('posts.index', [
            'posts' => $posts,
            'categories' => Category::orderBy('name')->get(),
            'selectedCategory' => $selectedCategory,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('posts.create', [
            'post' => new Post,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): RedirectResponse
    {
        $post = Post::create([
            ...$request->safe()->only(['title', 'category_id', 'excerpt', 'body']),
            'published_at' => $request->boolean('publish') ? now() : null,
        ]);

        return redirect()->route('posts.show', $post)->with('status', 'Post created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): View
    {
        $post->load('category');

        return view('posts.show', ['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post): View
    {
        return view('posts.edit', [
            'post' => $post,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        $post->fill($request->safe()->only(['title', 'category_id', 'excerpt', 'body']));

        if (! $request->boolean('publish')) {
            $post->published_at = null;
        } elseif ($post->published_at === null) {
            $post->published_at = now();
        }

        $post->save();

        return redirect()->route('posts.show', $post)->with('status', 'Post updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('posts.index')->with('status', 'Post deleted.');
    }
}
