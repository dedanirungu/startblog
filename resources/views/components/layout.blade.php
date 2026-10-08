@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title ? $title.' · ' : '' }}{{ config('app.name', 'Laravel') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-stone-50 text-stone-900 antialiased dark:bg-stone-950 dark:text-stone-100">
        <header class="border-b border-stone-200 bg-white dark:border-stone-800 dark:bg-stone-900">
            <nav class="mx-auto flex max-w-4xl flex-wrap items-center justify-between gap-4 px-4 py-4">
                <a href="{{ route('home') }}" class="text-lg font-semibold">{{ config('app.name', 'Laravel') }}</a>

                <div class="flex items-center gap-4 text-sm">
                    <a href="{{ route('home') }}" @class(['hover:underline', 'font-semibold' => request()->routeIs('home')])>Home</a>
                    <a href="{{ route('posts.index') }}" @class(['hover:underline', 'font-semibold' => request()->routeIs('posts.*')])>Posts</a>
                    <a href="{{ route('categories.index') }}" @class(['hover:underline', 'font-semibold' => request()->routeIs('categories.*')])>Categories</a>
                    <a href="{{ route('posts.create') }}" class="rounded-md bg-stone-900 px-3 py-1.5 font-medium text-white hover:bg-stone-700 dark:bg-stone-100 dark:text-stone-900 dark:hover:bg-stone-300">New post</a>
                </div>
            </nav>
        </header>

        <main class="mx-auto max-w-4xl px-4 py-8">
            @if (session('status'))
                <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-900 dark:bg-green-950 dark:text-green-200">
                    {{ session('status') }}
                </div>
            @endif

            {{ $slot }}
        </main>
    </body>
</html>
