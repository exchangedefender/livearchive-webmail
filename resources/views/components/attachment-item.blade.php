@props([
    'id',
    'name',
    'contentType',
])

@php

    $extension = strtolower(substr($name, strripos($name, '.'), strlen($name) - 1));
    $extension = str_replace(array(".", "docx", "xlsx", "pptx"), array("", "doc", "csv", "ppt"), $extension);

@endphp
<a
    {{$attributes}}
    {{$attributes->class(['w-70 flex items-center py-2.5 px-2 border-2 border-gray-300 rounded-lg hover:bg-gray-200'])}}
>

        <div class="flex items-center">
            <div class="w-10 flex items-center justify-center">

                <img src="{{ asset("storage/images/mime-icon/$extension.svg") }}" alt="" class="h-5 w-5" >
            </div>
            <div class="w-48 flex flex-col">
                <p class="text-sm text-light-font-msg dark:text-dark-font-header-msg font-bold truncate"> {{ $name ?: 'NO NAME' }}</p>
                <span class="text-gray-500 text-xs"></span>
            </div>
        </div>
        <button class="w-6 flex items-center justify-center" title="Download">
            <svg xmlns="http://www.w3.org/2000/svg" class="text-gray-500 hover:text-gray-600 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
        </button>

</a>
