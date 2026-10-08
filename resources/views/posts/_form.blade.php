@php
    $inputClasses = 'mt-1 block w-full rounded-md border border-stone-300 bg-white px-3 py-2 dark:border-stone-700 dark:bg-stone-900';
@endphp

<div class="flex flex-col gap-5">
    <div>
        <label for="title" class="block text-sm font-medium">Title</label>
        <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}" required maxlength="255" class="{{ $inputClasses }}">
        @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="category_id" class="block text-sm font-medium">Category</label>
        <select id="category_id" name="category_id" class="{{ $inputClasses }}">
            <option value="">Uncategorised</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) old('category_id', $post->category_id) === (string) $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        <p class="mt-1 text-xs text-stone-500"><a href="{{ route('categories.create') }}" class="underline">Add a new category</a></p>
    </div>

    <div>
        <label for="excerpt" class="block text-sm font-medium">Excerpt <span class="font-normal text-stone-500">(optional)</span></label>
        <textarea id="excerpt" name="excerpt" rows="2" maxlength="500" class="{{ $inputClasses }}">{{ old('excerpt', $post->excerpt) }}</textarea>
        @error('excerpt') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="body" class="block text-sm font-medium">Body</label>
        <textarea id="body" name="body" rows="14" required class="{{ $inputClasses }}">{{ old('body', $post->body) }}</textarea>
        @error('body') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <label class="flex items-center gap-2 text-sm">
        <input type="hidden" name="publish" value="0">
        <input type="checkbox" name="publish" value="1" @checked(old('publish', $post->exists ? $post->published_at !== null : true)) class="rounded border-stone-300">
        Published
    </label>
</div>
