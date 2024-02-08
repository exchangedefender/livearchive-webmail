@props(['name', 'text', 'required' => false])
<x-form-input :name="$name" :text="$text">
    <x-slot:input>
        <div class="@if($required|| $attributes->has('required')) required @endif peer relative">
            @if(isset($input))
                {{$input}}
            @else
            <input
                    {{
                        $attributes
                        ->class('peer px-4 py-2 border focus:ring-gray-500 focus:border-gray-900 w-full sm:text-sm border-gray-300 rounded-md focus:outline-none text-gray-600')
                        ->except(['name', 'type'])
                        ->merge(['name' => $name, 'type' => 'password', 'autocomplete' => 'off', 'id' => $name])
                   }}
                    @error($name)
                    aria-invalid="true"
                    aria-errormessage="{{$name}}-error"
                    @enderror
            >
            <button
                    type="button"
                    data-hs-toggle-password='{{\Illuminate\Support\Js::encode(["target" => "input[name={$name}]"])}}'
                    class="absolute top-0 end-0 px-3.5 py-2.5 rounded-e-md dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
            >
                <x-heroicon-o-eye-slash class="w-5 h-5 hidden hs-password-active:block"/>
                <x-heroicon-o-eye class="w-5 h-5 hs-password-active:hidden"/>
            </button>
            @endif
        </div>
    </x-slot:input>
</x-form-input>

