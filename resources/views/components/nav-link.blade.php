@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-500 dark:border-indigo-400 text-sm font-medium leading-5 text-indigo-600 dark:text-indigo-300 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-indigo-500 dark:hover:text-indigo-300 hover:border-indigo-300 dark:hover:border-indigo-500 focus:outline-none focus:text-indigo-600 dark:focus:text-indigo-400 focus:border-indigo-400 dark:focus:border-indigo-500 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
