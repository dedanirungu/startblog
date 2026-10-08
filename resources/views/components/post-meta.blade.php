@props(['post'])

<div class="flex flex-wrap items-center gap-2 text-xs text-stone-500 dark:text-stone-400">
    @if ($post->category)
        <a href="{{ route('posts.index', ['category' => $post->category->slug]) }}" class="font-medium uppercase tracking-wide hover:underline">{{ $post->category->name }}</a>
        <span>&middot;</span>
    @endif
    @if ($post->isPublished())
        <time datetime="{{ $post->published_at->toIso8601String() }}">{{ $post->published_at->toFormattedDateString() }}</time>
    @else
        <span class="rounded bg-amber-100 px-1.5 py-0.5 font-medium text-amber-800 dark:bg-amber-900 dark:text-amber-200">Draft</span>
    @endif
</div>
