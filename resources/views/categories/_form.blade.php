@php
    $inputClasses = 'mt-1 block w-full rounded-md border border-stone-300 bg-white px-3 py-2 dark:border-stone-700 dark:bg-stone-900';
@endphp

<div class="flex flex-col gap-5">
    <div>
        <label for="name" class="block text-sm font-medium">Name</label>
        <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required maxlength="255" class="{{ $inputClasses }}">
        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium">Description <span class="font-normal text-stone-500">(optional)</span></label>
        <textarea id="description" name="description" rows="3" maxlength="1000" class="{{ $inputClasses }}">{{ old('description', $category->description) }}</textarea>
        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>
