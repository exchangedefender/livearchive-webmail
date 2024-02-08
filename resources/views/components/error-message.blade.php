@props(['headline' => 'Error!', 'message' => 'Check for errors below.', 'show' => false])
<div id="dismiss-alert" class="@if(!$show)hidden @endif hs-removing:translate-x-5 hs-removing:opacity-0 transition duration-300 bg-red-50 border border-red-200 text-sm text-red-800 rounded-lg p-4 dark:bg-red-800/10 dark:border-red-900 dark:text-red-500" role="alert">
    <div class="flex">
        <div class="flex-shrink-0">
            <x-heroicon-o-exclamation-circle class="flex-shrink-0 h-4 w-4 text-red-600 mt-1"/>
        </div>
        <div class="ms-2">
            <div class="text-sm font-medium">
                {{Str::apa($message)}}
            </div>
        </div>
        <div class="ps-3 ms-auto">
            <div class="-mx-1.5 -my-1.5">
                <button type="button" class="inline-flex bg-red-50 rounded-lg p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-50 focus:ring-red-600 dark:bg-transparent dark:hover:bg-red-800/50 dark:text-red-600" data-hs-remove-element="#dismiss-alert">
                    <span class="sr-only">Dismiss</span>
                    <x-heroicon-s-x-mark class="flex-shrink-0 h-4 w-4"/>
                </button>
            </div>
        </div>
    </div>
</div>

{{--<div {{$attributes->class(["flex bg-red-100 rounded-lg p-4 mt-5 text-sm text-light-error dark:text-dark-error", "hidden" => !$show, "block" => $show])}} role="alert">--}}
{{--    @if(isset($body))--}}
{{--        {{$body}}--}}
{{--    @else--}}
{{--    <x-heroicon-s-exclamation-circle class="w-5 h-5 inline mr-3"/>--}}
{{--    <div>--}}
{{--        <span class="font-medium after:content-['_']">{{$headline}}</span>{{$message}}--}}
{{--    </div>--}}
{{--    @endif--}}
{{--</div>--}}
