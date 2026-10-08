<x-layout :title="$selectedCategory?->name ?? 'Posts'">
    <div class="flex flex-col gap-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ $selectedCategory?->name ?? 'All posts' }}</h1>
            @if ($selectedCategory?->description)
                <p class="mt-1 text-stone-600 dark:text-stone-400">{{ $selectedCategory->description }}</p>
            @endif
        </div>

        @if ($categories->isNotEmpty())
            <div class="flex flex-wrap gap-2 text-sm">
                <a href="{{ route('posts.index') }}" @class([
                    'rounded-full border px-3 py-1',
                    'border-stone-900 bg-stone-900 text-white dark:border-stone-100 dark:bg-stone-100 dark:text-stone-900' => ! $selectedCategory,
                    'border-stone-300 hover:bg-stone-100 dark:border-stone-700 dark:hover:bg-stone-800' => $selectedCategory,
                ])>All</a>
                @foreach ($categories as $category)
                    <a href="{{ route('posts.index', ['category' => $category->slug]) }}" @class([
                        'rounded-full border px-3 py-1',
                        'border-stone-900 bg-stone-900 text-white dark:border-stone-100 dark:bg-stone-100 dark:text-stone-900' => $selectedCategory?->is($category),
                        'border-stone-300 hover:bg-stone-100 dark:border-stone-700 dark:hover:bg-stone-800' => ! $selectedCategory?->is($category),
                    ])>{{ $category->name }}</a>
                @endforeach
            </div>
        @endif

        @forelse ($posts as $post)
            <article class="rounded-lg border border-stone-200 bg-white p-5 dark:border-stone-800 dark:bg-stone-900">
                <x-post-meta :post="$post" />

                <h2 class="mt-2 text-xl font-semibold">
                    <a href="{{ route('posts.show', $post) }}" class="hover:underline">{{ $post->title }}</a>
                </h2>

                <p class="mt-2 text-stone-600 dark:text-stone-400">{{ $post->excerpt ?: Str::limit($post->body, 200) }}</p>
            </article>
        @empty
            <p class="rounded-lg border border-dashed border-stone-300 p-8 text-center text-stone-500 dark:border-stone-700">
                No posts yet. <a href="{{ route('posts.create') }}" class="underline">Write the first one.</a>
            </p>
        @endforelse

        {{ $posts->links() }}
    </div>
</x-layout>
