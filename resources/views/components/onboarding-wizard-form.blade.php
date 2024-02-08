@props(['step', 'description'])
<form
    id="setup-form"
    hx-boost="true"
    hx-post
    _="on htmx:beforeRequest from #setup-form
            log 'form before'
            add .disabled to <#setup-form input, #setup-form button/>
            add [@@disabled=disabled] to <#setup-form input, #setup-form button/>
            send formSending(active: true) to .form-active-listener
        end
        on htmx:afterRequest from #setup-form
            if event.detail.failed
                remove .disabled from <#setup-form input, #setup-form button/>
                remove [@@disabled=disabled] from <#setup-form input, #setup-form button/>
            end
        end

    "
    {{$attributes->class(['w-full container group'])->merge(['method' => 'POST'])}}

>
    @if(isset($cover))
        {{$cover}}
    @else
        @if(isset($step))
        <div class="flex items-center space-x-5">
            @if(isset($icon))
            <div class="h-14 w-14 bg-light-icon-form dark:bg-dark-icon-form rounded-full flex flex-shrink-0 justify-center items-center text-light-font-icon-form dark:text-dark-font-icon-form text-2xl font-mono">
                {{$icon}}
            </div>
            @endif
            <div class="block pl-2 font-semibold text-xl self-start text-light-font dark:text-dark-font">
                <h2 class="leading-relaxed">{{$step}}</h2>
                <p class="text-sm text-gray-500 font-normal leading-relaxed text-light-font dark:text-dark-font/20">{{$description}}</p>
            </div>
        </div>
        @endif
    @endif
    {{$slot}}
    <hr class="mt-4 border-light-border dark:border-dark-border border-2">
    <div class="pt-4 flex flex-col md:flex-row items-center gap-4">
        @if(isset($actions))
            {{$actions}}
        @endif
        <button
                type="submit"
                class="gap-2 bg-light-btn-blue dark:bg-dark-hover-btn-blue/50 hover:bg-light-hover-btn-blue dark:hover:bg-dark-btn-blue/40 flex justify-center items-center w-full text-white px-4 py-3 rounded-md focus:outline-none disabled:bg-light-neutral dark:disabled:bg-dark-neutral group-disabled:bg-light-neutral dark:group-disabled:bg-dark-neutral"
        >

            <div class="form-indicator">
                <div class="-mx-4">
                    <x-si-spinrilla class="w-5 h-5 text-white animate-spin"/>
                </div>
            </div>
                Save
        </button>
    </div>
</form>
