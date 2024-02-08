<x-layout>

    <div id="main-content" class="h-full w-full bg-gray-50 relative bg-light-background dark:bg-dark-background dark:text-dark-font">
        <main>
            <div class="pt-6 px-4 ">

                <div class="grid grid-cols-1">
                    <div class="bg-white shadow rounded-lg mb-4 p-4 sm:p-6 h-full">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold leading-none text-gray-900">Mailboxes</h3>

                        </div>
                        <div class="flow-root">
                            <ul role="list" class="divide-y divide-gray-200">
                                @foreach($mailboxes->mailboxes as $mailbox)
                                    <li class="py-3 sm:py-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <div class="relative inline-flex items-center justify-center w-10 h-10 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-600">
                                                    <span class="font-medium text-gray-600 dark:text-gray-300">{{getInitials(parse_display_rfc822($mailbox->email))}}</span>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    <a href={{route('mailbox.inbox', ['mailbox' => $mailbox->email])}}>
                                                        {{ $mailbox->email }}
                                                    </a>
                                                </p>
{{--                                                <p class="text-sm text-gray-500 truncate">--}}
{{--                                                    <a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="17727a767e7b57607e7973646372653974787a">[email&#160;protected]</a>--}}
{{--                                                </p>--}}
                                            </div>
                                            <div class="inline-flex items-center text-base font-semibold text-gray-900">


                                                <a href={{route('mailbox.inbox', ['mailbox' => $mailbox->email])}}  class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 16">
                                                    <path  stroke="currentColor" d="m10.036 8.278 9.258-7.79A1.979 1.979 0 0 0 18 0H2A1.987 1.987 0 0 0 .641.541l9.395 7.737Z"/>
                                                    <path stroke="currentColor"  d="M11.241 9.817c-.36.275-.801.425-1.255.427-.428 0-.845-.138-1.187-.395L0 2.6V14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2.5l-8.759 7.317Z"/>
                                                </svg>
                                                    <span class="sr-only">Inbox</span>
                                                </a>


                                            </div>
                                        </div>



                                    </li>

                                @endforeach

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

{{--    <div hx-boost="true" hx-push-url="true">--}}
{{--        <p>Mailboxes</p>--}}
{{--        <ul>--}}
{{--            @foreach($mailboxes->mailboxes as $mailbox)--}}
{{--                <li>--}}
{{--                    <a href={{route('mailbox.inbox', ['mailbox' => $mailbox->email])}}>--}}
{{--                    {{ $mailbox->email }}--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            @endforeach--}}
{{--        </ul>--}}
{{--    </div>--}}
</x-layout>
