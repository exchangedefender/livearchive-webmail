<x-layout>
    <x-slot:status>
        <section id="download-status">
        @if(isset($download_link))
            <a href="{{$download_link}}" target="_blank"  class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                <svg class="w-3.5 h-3.5 me-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"/></svg>
                Download ready
            </a>
         @else
            <p
                hx-get="{{route('mailbox.download', ['mailbox' => $mailbox])}}"
                hx-trigger="every 5s"
                hx-select="#download-status"
                hx-target="#download-status"
            ><svg class="animate-spin h-5 w-5 mr-3 ..." viewBox="0 0 24 24">
                    <!-- ... -->
                </svg> Processing...
            </p>
        @endif
        </section>
    </x-slot:status>
    @fragment('progress')
        <section id="progress-download">
        <p>Downloading..</p>
        </section>
    @endfragment
</x-layout>
