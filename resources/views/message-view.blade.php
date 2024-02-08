<x-layout :mailbox="$mailbox">
    @if($compat)
    <x-slot name="top_nav">
        <p></p>
    </x-slot>
    @endif
    <div id="message-view-content" class="lg:container lg:mx-auto px-1 flex flex-col dark:bg-dark-bg-inside-msg dark:px-2 h-full">
        <header class="border-b-2 pb-5 flex flex-col">
                @if(layout_effective() === \App\Support\SiteLayoutStyle::OFFICE)
{{--                <div class="flex justify-between items-center h-20 py-2 block lg:hidden">--}}
{{--                    <div class="flex items-center shrink-0">--}}
{{--                        <div class="flex place-content-center text-gray-700  ">--}}
{{--                            <button--}}
{{--                                id="message-list-return-button"--}}
{{--                                _="--}}
{{--                                   on click send clearMessage to #message-list--}}
{{--                                "--}}
{{--                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"--}}
{{--                            >--}}
{{--                                <x-heroicon-s-arrow-left class="w-3.5 h-3.5 me-2 text-white dark:text-white"/>--}}
{{--                                Return--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
                @elseif(layout_effective() === \App\Support\SiteLayoutStyle::GMAIL)
                <div class="flex justify-between items-center h-20 py-2">
                    <div class="flex items-center shrink-0">
                        <div class="flex place-content-center text-gray-700  ">
                            <button
                                id="message-list-return-button"
                                hx-get="{{route('mailbox.inbox', [...request()->all(), 'mailbox' => $mailbox])}}"
                                hx-push-url="true"
                                hx-target="body" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-dark-btn-blue dark:hover:bg-dark-hover-btn-blue dark:focus:ring-blue-800"
                            >
                                <svg class="w-3.5 h-3.5 me-2 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"></path>
                                </svg>Return
                            </button>
                        </div>
                    </div>
                </div>

                @endif
            <div class="flex justify-between items-center h-32">
                <div class="flex space-x-4 items-center shrink-0">
                    <div class="relative inline-flex items-center justify-center w-10 h-10 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-600">
                        <span class="font-medium text-light-font-initial-msg dark:text-gray-300">{{getInitials(parse_display_rfc822($message_from))}}</span>
                    </div>
                    <div class="flex flex-col">
                        @if(filled(parse_display_rfc822($message_from)))
                            <h3 class="font-semibold text-lg dark:text-dark-font-header-msg">{{parse_display_rfc822($message_from)}}</h3>
                        @endif
                        <p class="text-light text-light-font-header-msg dark:text-dark-font-header-msg">{{parse_address_rfc822($message_from)}}</p>
                    </div>
                </div>
                <div>
                    <ul class="flex text-light-font-header-msg space-x-4">
                        <li><button type="button" onclick="printMsg()" class="print:print-message-view-content text-gray-600 dark:text-gray-700" id="btn-print-msg"><x-heroicon-o-printer class="w-6 h-6 "/>
                            </button>
                        </li>
                        <li><a
                                type="button"
                                class="text-light-font-initial-msg dark:text-dark-font-header-msg"
                                target="_blank"
                                href="{{route('mailbox.message-download', ['mailbox' => $mailbox, 'message' => $message])}}"
                            >
                                <x-heroicon-o-arrow-down-tray class="w-6 h-6"/>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            @if(layout_effective() === \App\Support\SiteLayoutStyle::OFFICE && $message_attachments->count() > 0)

            <div class="flex min-h-16 content-start hidden-print-view border-dashed border-t-1 ">
                <div class="flex space-x-4 py-4 dark:text-dark-font-header-msg">
                @foreach($message_attachments as $message_attachment)

                   <x-attachment-item
                       href="{{route('mailbox.attachment-download', ['mailbox' => $mailbox, 'message' => $message, 'attachment' => $message_attachment->id])}}"
                    target="_blank"
                    :name="$message_attachment->filename"
                    :content-type="$message_attachment->contentType"
                />
                @endforeach
                </div>
            </div>
            @endif

        </header>
        <span class="font-bold block pt-2 dark:text-dark-font-header-msg">{{$message_subject}}</span>

        <iframe
            onload="resizeIframe(this)"
            class="dark:bg-dark-bg-inside-msg w-full flex-grow  text-light-font-msg dark:text-dark-font-msg pb-2 overflow-y-hidden lg:max-h-[80dvh] lg:overflow-y-auto"
            src={{route('mailbox.message-body', ['message' => $message, 'mailbox' => $mailbox])}}
            sandbox="allow-top-navigation-by-user-activation allow-same-origin" ></iframe>

        <div class="container mx-auto px-1 hidden-print-view">
            @if(layout_effective() === \App\Support\SiteLayoutStyle::GMAIL)
                <div class="border-t-2 flex min-h-16 content-start">

                    <div class="flex space-x-4 py-4">
                        @foreach($message_attachments as $message_attachment)
                            <x-attachment-item
                                href="{{route('mailbox.attachment-download', ['mailbox' => $mailbox, 'message' => $message, 'attachment' => $message_attachment->id])}}"
                                target="_blank"
                                :name="$message_attachment->filename"
                                :content-type="$message_attachment->contentType"
                            />


                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>


</x-layout>
