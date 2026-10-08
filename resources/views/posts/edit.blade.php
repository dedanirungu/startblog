<x-layout :title="'Edit '.$post->title">
    <h1 class="mb-6 text-2xl font-semibold">Edit post</h1>

    <form method="POST" action="{{ route('posts.update', $post) }}" class="flex flex-col gap-6">
        @csrf
        @method('PUT')

        @include('posts._form')

        <div class="flex items-center gap-4">
            <button type="submit" class="rounded-md bg-stone-900 px-4 py-2 font-medium text-white hover:bg-stone-700 dark:bg-stone-100 dark:text-stone-900 dark:hover:bg-stone-300">Save changes</button>
            <a href="{{ route('posts.show', $post) }}" class="text-sm hover:underline">Cancel</a>
        </div>
    </form>
</x-layout>
