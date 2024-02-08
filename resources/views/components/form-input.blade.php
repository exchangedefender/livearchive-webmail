@props([
    'name',
    'text'
])
<div class="flex flex-col-reverse text-light-font dark:text-dark-font gap-1">
    @if(isset($label))
        {{$label}}
    @else
    <span id="{{$name}}-error" class="ml-2 block text-sm break-words text-light-error dark:text-dark-error @error($name) visible @else hidden @enderror">@error($name){{$message}}@enderror</span>
    @endif
    @if(isset($description))
        <small class="font-thin text-xs text-light-font/50 dark:text-dark-font/50">
        {{$description}}
        </small>
    @endif
    @if(isset($input))
        {{$input}}
    @else
    <input type="text"
           {{
                $attributes
                ->except(['name'])
                ->merge(['name' => $name])
                ->class('peer px-4 border focus:ring-gray-500 focus:border-gray-900 w-full sm:text-sm border-gray-300 dark:bg-transparent dark:text-dark-font rounded-md focus:outline-none text-light-font')
           }}
           @error($name)
           aria-invalid="true"
           aria-errormessage="{{$name}}-error"
           @enderror
    >
    @endif
    @if(isset($label))
        {{$label}}
    @else
        <x-form-input-label>{{$text}}</x-form-input-label>
    @endif

</div>
