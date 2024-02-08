@props([
    'name',
    'text'
])
<div class="flex flex-col-reverse">
    <span id="timezone-error" class="ml-2 block text-sm text-light-error @error($name) visible @else hidden @enderror">@error($name){{$message}}@enderror</span>
    <select
            {{
                $attributes
                ->class(['peer px-4 py-2 border focus:ring-gray-500 focus:border-gray-900 w-full sm:text-sm border-gray-300 dark:bg-transparent dark:text-dark-font rounded-md focus:outline-none text-light-font'])
                ->except(['name'])
                ->merge([
                    'name' => $name
                ])
             }}
            @error($name)
            aria-invalid="true"
            aria-errormessage="{{$name}}-error"
            @enderror
    >
        {{$slot}}
    </select>
    @if(isset($label))
        {{$label}}
    @else
        <x-form-input-label>{{$text}}</x-form-input-label>
    @endif
</div>
