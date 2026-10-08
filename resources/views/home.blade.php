<x-layout>
    <div class="flex flex-col gap-10">
        <section class="flex flex-col items-start gap-4 rounded-xl bg-stone-900 px-6 py-10 text-white sm:px-10 dark:bg-stone-100 dark:text-stone-900">
            <h1 class="text-3xl font-bold sm:text-4xl">{{ config('app.name', 'Laravel') }}</h1>
            <p class="max-w-xl text-stone-300 dark:text-stone-600">Stories, tutorials and ideas. Browse the latest posts or share one of your own.</p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('posts.create') }}" class="rounded-md bg-white px-4 py-2 font-medium text-stone-900 hover:bg-stone-200 dark:bg-stone-900 dark:text-white dark:hover:bg-stone-700">Write a post</a>
                <a href="{{ route('posts.index') }}" class="rounded-md border border-stone-600 px-4 py-2 font-medium hover:bg-stone-800 dark:border-stone-400 dark:hover:bg-stone-200">All posts</a>
            </div>
        </section>

        @if ($featuredPost)
            <div class="grid gap-10 md:grid-cols-[1fr_14rem]">
                <div class="flex flex-col gap-8">
                    <article class="flex flex-col gap-3">
                        <x-post-meta :post="$featuredPost" />
                        <h2 class="text-2xl font-bold">
                            <a href="{{ route('posts.show', $featuredPost) }}" class="hover:underline">{{ $featuredPost->title }}</a>
                        </h2>
                        <p class="text-stone-600 dark:text-stone-400">{{ $featuredPost->excerpt ?: Str::limit($featuredPost->body, 300) }}</p>
                        <a href="{{ route('posts.show', $featuredPost) }}" class="text-sm font-medium hover:underline">Read more &rarr;</a>
                    </article>

                    @if ($recentPosts->isNotEmpty())
                        <section class="flex flex-col gap-4 border-t border-stone-200 pt-8 dark:border-stone-800">
                            <h2 class="text-sm font-semibold uppercase tracking-wide text-stone-500">Recent posts</h2>
                            <div class="grid gap-4 sm:grid-cols-2">
                                @foreach ($recentPosts as $post)
                                    <article class="flex flex-col gap-2 rounded-lg border border-stone-200 bg-white p-5 dark:border-stone-800 dark:bg-stone-900">
                                        <x-post-meta :post="$post" />
                                        <h3 class="font-semibold">
                                            <a href="{{ route('posts.show', $post) }}" class="hover:underline">{{ $post->title }}</a>
                                        </h3>
                                        <p class="text-sm text-stone-600 dark:text-stone-400">{{ Str::limit($post->excerpt ?: $post->body, 120) }}</p>
                                    </article>
                                @endforeach
                            </div>
                            <a href="{{ route('posts.index') }}" class="self-start text-sm font-medium hover:underline">View all posts &rarr;</a>
                        </section>
                    @endif
                </div>

                <aside class="flex flex-col gap-3">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-stone-500">Categories</h2>
                    @forelse ($categories as $category)
                        <a href="{{ route('posts.index', ['category' => $category->slug]) }}" class="flex items-center justify-between gap-2 text-sm hover:underline">
                            <span>{{ $category->name }}</span>
                            <span class="text-stone-500">{{ $category->posts_count }}</span>
                        </a>
                    @empty
                        <p class="text-sm text-stone-500">No categories yet.</p>
                    @endforelse
                    <a href="{{ route('categories.create') }}" class="mt-2 text-sm text-stone-500 hover:underline">+ Add category</a>
                </aside>
            </div>
        @else
            <div class="flex flex-col items-center gap-4 rounded-lg border border-dashed border-stone-300 p-12 text-center dark:border-stone-700">
                <p class="text-stone-500">No published posts yet.</p>
                <a href="{{ route('posts.create') }}" class="rounded-md bg-stone-900 px-4 py-2 font-medium text-white hover:bg-stone-700 dark:bg-stone-100 dark:text-stone-900 dark:hover:bg-stone-300">Write the first post</a>
            </div>
        @endif
    </div>
</x-layout>
