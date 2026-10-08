<x-layout title="Categories">
    <div class="mb-6 flex items-center justify-between gap-4">
        <h1 class="text-2xl font-semibold">Categories</h1>
        <a href="{{ route('categories.create') }}" class="rounded-md border border-stone-300 px-3 py-1.5 text-sm hover:bg-stone-100 dark:border-stone-700 dark:hover:bg-stone-800">New category</a>
    </div>

    @if ($categories->isEmpty())
        <p class="rounded-lg border border-dashed border-stone-300 p-8 text-center text-stone-500 dark:border-stone-700">
            No categories yet. <a href="{{ route('categories.create') }}" class="underline">Create one.</a>
        </p>
    @else
        <ul class="divide-y divide-stone-200 rounded-lg border border-stone-200 bg-white dark:divide-stone-800 dark:border-stone-800 dark:bg-stone-900">
            @foreach ($categories as $category)
                <li class="flex flex-wrap items-center gap-4 px-5 py-4">
                    <div class="min-w-0 flex-1">
                        <a href="{{ route('categories.show', $category) }}" class="font-medium hover:underline">{{ $category->name }}</a>
                        <span class="ml-2 text-sm text-stone-500">{{ trans_choice('{0} No posts|{1} :count post|[2,*] :count posts', $category->posts_count) }}</span>
                        @if ($category->description)
                            <p class="mt-1 text-sm text-stone-600 dark:text-stone-400">{{ $category->description }}</p>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 text-sm">
                        <a href="{{ route('categories.edit', $category) }}" class="rounded-md px-2 py-1 hover:bg-stone-100 dark:hover:bg-stone-800">Edit</a>
                        <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Delete this category? Its posts will become uncategorised.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-md px-2 py-1 text-red-600 hover:bg-red-50 dark:hover:bg-red-950">Delete</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</x-layout>
