<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LaravelArticleSeeder extends Seeder
{
    /**
     * Seed five sample articles about Laravel.
     */
    public function run(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'laravel'],
            ['name' => 'Laravel', 'description' => 'Guides and tips for building applications with Laravel.'],
        );

        foreach ($this->articles() as $daysAgo => $article) {
            Post::updateOrCreate(
                ['slug' => Str::slug($article['title'])],
                [
                    ...$article,
                    'category_id' => $category->id,
                    'published_at' => now()->subDays(($daysAgo + 1) * 3),
                ],
            );
        }
    }

    /**
     * Get the sample articles, newest first.
     *
     * @return list<array{title: string, excerpt: string, body: string}>
     */
    private function articles(): array
    {
        return [
            [
                'title' => 'Getting Started with Laravel: Routes, Controllers and Views',
                'excerpt' => 'A quick tour of how a request travels through a Laravel application, from the route file to the rendered page.',
                'body' => <<<'TEXT'
                    Every Laravel application starts with a request. When a browser asks for a URL, Laravel looks it up in routes/web.php and decides which piece of code should answer it.

                    The simplest route returns a view directly: Route::get('/', fn () => view('welcome')). That works for static pages, but as soon as a page needs data you will want a controller.

                    Controllers group related request handling into one class. Running php artisan make:controller PostController --resource gives you seven methods — index, create, store, show, edit, update and destroy — that map neatly onto Route::resource('posts', PostController::class).

                    Inside a controller method you fetch whatever the page needs and hand it to a Blade view: return view('posts.index', ['posts' => Post::latest()->paginate(10)]). Blade then renders the HTML, escaping output by default with the {{ }} syntax so user content cannot inject scripts.

                    Once you are comfortable with this route → controller → view loop, you understand the backbone of almost every Laravel page you will ever build.
                    TEXT,
            ],
            [
                'title' => 'Eloquent Relationships Explained',
                'excerpt' => 'belongsTo, hasMany and belongsToMany cover most real-world data models. Here is how to define and use each one.',
                'body' => <<<'TEXT'
                    Eloquent is Laravel's ORM, and relationships are where it really shines. Instead of writing joins by hand, you describe how your models relate and let Eloquent build the queries.

                    A blog post that belongs to a category has a category_id column, so the Post model defines public function category(): BelongsTo { return $this->belongsTo(Category::class); }. The inverse lives on Category as a hasMany relationship called posts.

                    With both sides defined you can write $post->category->name or $category->posts()->latest()->get() and Eloquent fills in the SQL for you.

                    Many-to-many relationships, such as posts and tags, need a pivot table named post_tag with post_id and tag_id columns. Each model then declares belongsToMany, and you can attach, detach or sync related records in a single call.

                    Always add the return type to relationship methods. It documents intent, helps your editor autocomplete, and makes static analysis tools far more useful.
                    TEXT,
            ],
            [
                'title' => 'Validating Requests with Form Requests',
                'excerpt' => 'Move validation out of your controllers and into dedicated Form Request classes for cleaner, reusable code.',
                'body' => <<<'TEXT'
                    Validating input inline with $request->validate([...]) is fine for small forms, but controllers quickly become cluttered as rules grow.

                    Form Requests solve this. Run php artisan make:request StorePostRequest and you get a class with two methods: authorize, which decides whether the current user may make the request, and rules, which returns the validation rules.

                    Type-hint the Form Request in your controller method and Laravel validates the input before your code even runs. If validation fails, the user is redirected back with errors and their old input, ready to display with the @error directive and the old() helper.

                    Inside the controller, use $request->validated() or $request->safe()->only([...]) rather than $request->all(). That way only the fields you have explicitly validated reach your models, which protects you from mass-assignment surprises.

                    Prefer array syntax for rules, such as ['required', 'string', 'max:255']. It reads well and lets you mix in rule objects like Rule::unique('categories')->ignore($category) without awkward string concatenation.
                    TEXT,
            ],
            [
                'title' => 'Fixing the N+1 Query Problem with Eager Loading',
                'excerpt' => 'One innocent loop can fire hundreds of queries. Learn to spot N+1 problems and fix them with a single with() call.',
                'body' => <<<'TEXT'
                    Imagine listing 50 posts and showing each post's category name. Fetching the posts takes one query, but accessing $post->category inside the loop triggers another query for every post. That is 51 queries for one page — the classic N+1 problem.

                    The fix is eager loading. Change Post::latest()->get() to Post::with('category')->latest()->get() and Laravel loads all the categories in a single extra query, no matter how many posts there are.

                    You can eager load nested relationships with dot notation, such as with('comments.author'), and constrain them with a closure when you only need a subset of related records.

                    Counting is a common variant of the same problem. Instead of calling $category->posts->count() in a loop, use Category::withCount('posts') and read the posts_count attribute.

                    To catch N+1 problems early, add Model::preventLazyLoading(! app()->isProduction()) to a service provider. Laravel will then throw an exception during development whenever a relationship is lazily loaded.
                    TEXT,
            ],
            [
                'title' => 'Running Background Work with Laravel Queues',
                'excerpt' => 'Send emails, process uploads and call slow APIs without making your users wait, using Laravel queued jobs.',
                'body' => <<<'TEXT'
                    Some work simply does not belong in the request cycle. Sending a welcome email, resizing an image or syncing with a third-party API can take seconds, and your users should not have to wait for it.

                    Laravel queues let you push that work into the background. Generate a job with php artisan make:job SendWelcomeEmail, put the slow logic in its handle method, and dispatch it with SendWelcomeEmail::dispatch($user).

                    A queue worker, started with php artisan queue:work, picks jobs off the queue and runs them. New applications use the database driver by default, which needs no extra infrastructure; Redis or SQS are good choices as traffic grows.

                    Jobs can fail, so plan for it. Set $tries and $backoff on the job to retry transient errors, and implement a failed method to log or notify when a job gives up for good.

                    In tests, call Queue::fake() and then assert with Queue::assertPushed(SendWelcomeEmail::class). Your tests stay fast and you still verify that the right work was scheduled.
                    TEXT,
            ],
        ];
    }
}
