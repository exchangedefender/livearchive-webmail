<div id="topbar-search-actions" class="absolute inset-y-0 right-0 pr-3 flex items-center">
    @php
        $filtered = $filter?->enabled || filled($search);
    @endphp
    <div id="topbar-clear-search-root" @class(["hs-tooltip [--placement:bottom]", 'inline-block' => $filtered, 'hidden' => !$filtered])>
        <a
            href="{{route('mailbox.inbox', ['mailbox' => $mailbox])}}"
            hx-boost="true"
            hx-target="body"
        >
            <button
                type="button"
                class="hover:bg-gray-300 text-gray-500 hover:text-white p-2 rounded-full"
            >
                <x-heroicon-s-x-mark class="h-5 w-5" />
                <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-gray-900 text-xs font-medium text-white rounded shadow-sm dark:bg-dark-background" role="tooltip">
                  Clear search
                </span>
            </button>
        </a>
    </div>
    <div id="search-filter-tooltip" class="hs-tooltip inline-block [--placement:bottom-right]">
        <div class="hs-tooltip-toggle block text-center">
            <button
                type="button"
                class="hover:bg-gray-300 text-gray-500 hover:text-white p-2 rounded-full"
                hx-on:click="HSTooltip.show(document.querySelector('#search-filter-tooltip'))"
            >
                <x-heroicon-s-bars-arrow-down class="h-5 w-5" />
            </button>
            <div class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible hidden hs-tooltip-shown:!-mr-4 opacity-0 transition-opacity inline-block fixed invisible z-10 bg-white border border-gray-100 text-start rounded-lg shadow-md dark:bg-dark-background dark:border-gray-700" role="tooltip">
                <form
                    id="filter-form"
                    action="{{route('mailbox.inbox', ['mailbox' => $mailbox], false)}}"
                >
                    <input type="hidden" name="filter" value="on"/>
                    <div class="grid grid-rows-4 gap-2 items-end grid-cols-4 p-4 w-64 lg:w-96 ">
                            <label class="col-span-1 block" for="sender">From</label>
                            <input value="{{old('sender', $filter?->sender)}}" name="sender" type="text" class="form-resettable col-span-3 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-gray-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-500 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none dark:border-b-gray-700 dark:text-gray-400 dark:focus:ring-gray-600 dark:focus:border-b-gray-600">
                            <label class="col-span-1 block" for="sender">Subject</label>
                            <input value="{{old('subject', $filter?->subject)}}" name="subject" type="text" class="form-resettable col-span-3 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-gray-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-500 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none dark:border-b-gray-700 dark:text-gray-400 dark:focus:ring-gray-600 dark:focus:border-b-gray-600">
                            <label class="col-span-1 block" for="date">Date</label>
                            <input
                                name="date"
                                id="search-filter-date"
                                type="text"
                                value="{{old('date', $filter?->date)}}"
                                class="form-resettable col-span-3 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-gray-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-500 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none dark:border-b-gray-700 dark:text-gray-400 dark:focus:ring-gray-600 dark:focus:border-b-gray-600"
                                _='init
                                    set el to me js(el) new AirDatepicker(el, {locale: HSTooltipEn, range: true, position: "bottom left", container: ".hs-tooltip-content",  selectedDates:  @json($filter?->dates(config('app.local_timezone')))})
                                '
                            >
                            @if($filter?->enabled)
                            <a
                                    href="{{route('mailbox.inbox', ['mailbox' => $mailbox])}}"
                                    class="col-start-2 col-span-2 py-2 px-8 inline-flex items-center  text-sm font-semibold rounded-lg border border-transparent text-red-600 hover:bg-red-800 hover:text-white disabled:opacity-50 disabled:pointer-events-none dark:text-red-500 dark:hover:bg-red-800 dark:hover:text-white dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"

                            >Remove filters</a>
                            @else
                                <button type="reset" class="col-start-3 py-2 px-4 items-center text-sm font-semibold rounded-lg border border-transparent text-red-600 hover:bg-red-800 hover:text-white disabled:opacity-50 disabled:pointer-events-none dark:text-red-500 dark:hover:bg-red-800 dark:hover:text-white dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                    Reset
                                </button>
                            @endif
                            <button
                                type="submit"
                                class="col-start-4 py-2 px-4 items-center text-center text-sm font-semibold rounded-lg border border-transparent   disabled:opacity-50 disabled:pointer-events-none text-blue-600 hover:bg-blue-800 hover:text-white  dark:text-blue-500 dark:hover:bg-blue-800 dark:hover:text-white dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                            >
                                Filter
                            </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
