<div id="inbox-root" class="flex flex-col flex-nowrap items-stretch h-[90cqh] overflow-y-auto pr-2 text-gray-700 dark:text-dark-font-list" hx-boost>
    <div class="h-16 flex items-center justify-between sticky top-0 bg-white py-2 dark:bg-dark-background">
        <div class="flex justify-between w-full">
            <div class="relative flex items-center px-0.5 space-x-0.5">
{{--action bar buttons--}}
{{--                    <div class="flex items-center ml-3">--}}
{{--                        <button title="Reload" class="text-gray-700 px-2 py-1 border border-gray-300 rounded-lg shadow hover:bg-gray-200 transition duration-100">--}}
{{--                            <x-heroicon-o-arrow-path class="w-5 h-5"/>--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                    <span class="bg-gray-300 h-6 w-[.5px] mx-3"></span>--}}
{{--                    <div class="flex items-center space-x-2">--}}
{{--                        <button title="Archive" class="text-gray-700 px-2 py-1 border border-gray-300 rounded-lg shadow hover:bg-gray-200 transition duration-100">--}}
{{--                            <x-heroicon-o-archive-box-arrow-down class="w-5 h-5"/>--}}
{{--                        </button>--}}
{{--                        <button title="Mark As Spam" class="text-gray-700 px-2 py-1 border border-gray-300 rounded-lg shadow hover:bg-gray-200 transition duration-100">--}}
{{--                            <x-heroicon-o-exclamation-triangle class="w-5 h-5"/>--}}
{{--                        </button>--}}
{{--                        <button title="Delete" class="text-gray-700 px-2 py-1 border border-gray-300 rounded-lg shadow hover:bg-gray-200 transition duration-100">--}}
{{--                            <x-heroicon-o-trash class="w-5 h-5"/>--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                    <span class="bg-gray-300 h-6 w-[.5px] mx-3"></span>--}}
{{--                    <div class="flex items-center space-x-2">--}}
{{--                        <button title="Mark As Read" class="text-gray-700 px-2 py-1 border border-gray-300 rounded-lg shadow hover:bg-gray-200 transition duration-100">--}}
{{--                            <x-heroicon-o-envelope class="w-5 h-5"/>--}}
{{--                        </button>--}}
{{--                        <button title="Mark As Unread" class="text-gray-700 px-2 py-1 border border-gray-300 rounded-lg shadow hover:bg-gray-200 transition duration-100">--}}
{{--                            <x-heroicon-o-envelope-open class="w-5 h-5"/>--}}
{{--                        </button>--}}
{{--                        <button title="Add Star" class="text-gray-700 px-2 py-1 border border-gray-300 rounded-lg shadow hover:bg-gray-200 transition duration-100">--}}
{{--                            <x-heroicon-o-star class="w-5 h-5"/>--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{-- pagination info               --}}
            </div>
                @fragment("messages-meta")
                @inject('archiveDatabase', 'App\Contracts\ProvidesArchiveDatabase')
                <div id="messages-meta" class="px-2 relative">
                    <div class="flex items-center space-x-2">
                        <button @disabled(empty($page_url_prev))
                                hx-get="{{$page_url_prev}}"
                                hx-select="#messages-list"
                                hx-target="#messages-list"
                                hx-select-oob="#messages-meta"
                                title="Previous Page"
                                class="bg-gray-200 dark:bg-inherit disabled:text-gray-400 text-black p-1.5 rounded-lg"
                        >
                            @if($archiveDatabase->hasArchiveDatabase())
                                <x-heroicon-s-chevron-left class="w-5 h-5"/>
                            @else
                                <x-heroicon-s-chevron-double-left class="w-5 h-5"/>
                            @endif
                        </button>
                        <button @disabled(empty($page_url_next))
                                hx-get="{{$page_url_next}}"
                                hx-select="#messages-list"
                                hx-target="#messages-list"
                                hx-select-oob="#messages-meta"
{{--                                _="on htmx:afterRequest set $prev_url to detail.requestConfig.path"--}}
                                @if($page_url_push)
                                hx-push-url="true"
                                @endif
                                title="Next Page"
                                class="bg-gray-200 dark:bg-inherit disabled:text-gray-400 text-black dark:text-dark-font p-1.5 rounded-lg"
                        >
                            <x-heroicon-s-chevron-right class="w-5 h-5"/>
                        </button>
                    </div>
                </div>
            @endfragment
        </div>
    </div>
    @fragment("messages-list")
    <div id="messages-container">
        @if($messages->messages->count() === 0)
           <div class="grid place-content-center">

               <div class="lg:px-10 lg:py-24 md:py-20 md:px-44 px-4 py-24 items-center flex justify-center flex-col-reverse lg:flex-row md:gap-28 gap-16 dark:bg-dark-bg-inside-msg">


                   <div class="xl:pt-15 w-full xl:w-3/4 relative pb-12 lg:pb-0">

                       <div class="relative">
                           <div class="absolute">
                               <div class="pt-3">
                                   <h6 class="block mb-4  text-6xl font-sans text-base antialiased font-semibold leading-relaxed tracking-normal text-red-500 uppercase">
                                       Error!
                                   </h6>
                                   <h1 class="my-2 text-gray-800 font-bold text-4xl">
                                       No messages found
                                   </h1>
                                          </div>
                           </div>
                           <div>
                               <img src="{{asset('storage/images/error/404.png')}}" />
                           </div>
                       </div>
                   </div>
                   <div>
                       <svg id="fi_7390085" height="230" viewBox="0 0 64 64" width="512" xmlns="http://www.w3.org/2000/svg" data-name="Layer 3"><path d="m60.407 22.791a4.031 4.031 0 0 1 1.593 3.209v33a3 3 0 0 1 -3 3h-54a3 3 0 0 1 -3-3v-33a4.031 4.031 0 0 1 1.593-3.209l26.607-20.191a2.974 2.974 0 0 1 1.8-.6 2.974 2.974 0 0 1 1.8.6z" fill="#f79b31"></path><path d="m60.407 22.791-26.607-20.191a2.975 2.975 0 0 0 -3.594 0l-22.376 16.977a4.015 4.015 0 0 0 -.83 2.423v33a3 3 0 0 0 3 3h36a2 2 0 0 1 0 4h13a3 3 0 0 0 3-3v-33a4.031 4.031 0 0 0 -1.593-3.209zm-29.407 30.209a12 12 0 1 1 12-12 12 12 0 0 1 -12 12z" fill="#fcb732"></path><path d="m32 41a3.8 3.8 0 0 0 1.87-.493l27.888-15.807a4.006 4.006 0 0 0 -1.351-1.913l-26.607-20.187a2.975 2.975 0 0 0 -3.594 0l-26.613 20.191a4.006 4.006 0 0 0 -1.351 1.909l27.888 15.8a3.8 3.8 0 0 0 1.87.5z" fill="#ec8329"></path><path d="m55 9v19.53l-21.13 11.98a3.813 3.813 0 0 1 -3.74 0l-21.13-11.98v-19.53a3 3 0 0 1 3-3h40a3 3 0 0 1 3 3z" fill="#ccc"></path><path d="m52 6h-40v16.385a9.746 9.746 0 0 0 4.858 8.431l13.272 7.694a3.813 3.813 0 0 0 3.74 0l21.13-11.98v-17.53a3 3 0 0 0 -3-3z" fill="#f2f2f2"></path><path d="m41 53h18v2h-18z" fill="#fff"></path><path d="m35 57h24v2h-24z" fill="#fff"></path><g fill="#ccc"><path d="m12 19.001h40v2h-40z"></path><path d="m12 22.999h40v2h-40z"></path><path d="m12 27h10v2h-10z"></path><path d="m17.999 11h28v2h-28z"></path><path d="m12 14.999h40v2h-40z"></path></g><circle cx="32" cy="40" fill="#e73844" r="12"></circle><path d="m32 28a11.93 11.93 0 0 0 -7.419 2.581 11.988 11.988 0 0 0 16.838 16.838 11.988 11.988 0 0 0 -9.419-19.419z" fill="#ff4d55"></path><path d="m39 36-3-3-4 4-4-4-3 3 4 4-4 4 3 3 4-4 4 4 3-3-4-4z" fill="#fff"></path></svg>
                   </div>
               </div>


            </div>
        @else
        <ul id="messages-list" class="overflow-y-auto">
{{--            @for($i = 0; $i < 2; $i++)--}}
            @foreach($messages->messages as $message)
                <li
                    class="items-center border-y hover:bg-gray-200 px-2 dark:hover:bg-dark-hover-list-gmail dark:text-dark-font"
                    hx-get="{{route('mailbox.message-view', ['mailbox' => $mailbox->username, 'message' => Crypt::encryptString($message->path)])}}"
                    hx-trigger="click"
                    hx-push-url="true"
                    hx-target="#inbox-root"
                    hx-select="#message-view-content"
                    key={{md5($message->path)}}
                >
                    <div class="w-full grid auto-rows-fr items-center justify-between p-1 my-1 cursor-pointer">
                        <div class="grid grid-rows-2 lg:grid-rows-1 grid-cols-6 lg:grid-cols-12 items-center flex-nowrap overflow-x-hidden">
{{--                                <div class="flex items-center mr-4 ml-1 space-x-1">--}}
{{--                                    <x-heroicon-o-star class="text-gray-500 hover:text-gray-900 h-5 w-5  dark:text-dark-icons"/>--}}
{{--                                </div>--}}
                            <span class="row-start-1 lg:row-start-auto col-span-2 inline-block w-80 pr-2 truncate shrink ">{{ parse_display_rfc822($message->meta?->sender_envelope ?: $message->meta?->sender ?: '<>') }}</span>
                            <div class="row-start-2 lg:row-start-auto col-span-full lg:col-span-8">
                                <div class="flex-grow truncate overflow-x-hidden">
                                <span class="min-w-64 truncate">{{$message->meta?->subject ?: 'NO SUBJECT'}}</span>
                                <span class="mx-1 font-semibold">-</span>
                                <span class="w-96 text-gray-600 dark:text-dark-description text-sm truncate">{{$message->meta?->preview ?: ''}}</span>
                                </div>
                            </div>
                            <div class="row-start-1 lg:row-start-auto col-start-5 lg:col-start-auto col-span-2 min-w-max flex items-center justify-end gap-4">
                                <x-heroicon-s-paper-clip @class(['w-5 h-5 text-gray-500 dark:text-dark-icons-attachment', 'invisible' => $message->meta?->attachment_count === 0]) />
                                <x-timestamp class="w-36 text-end text-gray-500 dark:text-dark-icons-attachment" :timestamp="$message->meta?->timestamp ?? now()" :relative="false"/>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
{{--            @endfor--}}
        </ul>
        @endif
        </div>
        @endfragment
</div>
