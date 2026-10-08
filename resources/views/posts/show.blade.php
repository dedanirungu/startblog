<x-layout :title="$post->title">
    <article class="flex flex-col gap-6">
        <header class="flex flex-col gap-2">
            <div class="flex flex-wrap items-center gap-2 text-sm text-stone-500 dark:text-stone-400">
                @if ($post->category)
                    <a href="{{ route('posts.index', ['category' => $post->category->slug]) }}" class="font-medium uppercase tracking-wide hover:underline">{{ $post->category->name }}</a>
                    <span>&middot;</span>
                @endif
                @if ($post->isPublished())
                    <time datetime="{{ $post->published_at->toIso8601String() }}">{{ $post->published_at->toFormattedDateString() }}</time>
                @else
                    <span class="rounded bg-amber-100 px-1.5 py-0.5 text-xs font-medium text-amber-800 dark:bg-amber-900 dark:text-amber-200">Draft</span>
                @endif
            </div>

            <h1 class="text-3xl font-bold">{{ $post->title }}</h1>

            @if ($post->excerpt)
                <p class="text-lg text-stone-600 dark:text-stone-400">{{ $post->excerpt }}</p>
            @endif
        </header>

        <div class="flex flex-col gap-4 leading-relaxed">
            @foreach (preg_split('/\R{2,}/', trim($post->body)) as $paragraph)
                <p>{!! nl2br(e($paragraph)) !!}</p>
            @endforeach
        </div>

        <footer class="flex items-center gap-4 border-t border-stone-200 pt-6 text-sm dark:border-stone-800">
            <a href="{{ route('posts.edit', $post) }}" class="rounded-md border border-stone-300 px-3 py-1.5 hover:bg-stone-100 dark:border-stone-700 dark:hover:bg-stone-800">Edit</a>

            <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('Delete this post?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-md px-3 py-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-950">Delete</button>
            </form>

            <a href="{{ route('posts.index') }}" class="ml-auto hover:underline">&larr; All posts</a>
        </footer>
    </article>
</x-layout>
