<div class="flex flex-nowrap items-stretch  grow-1 app-root pr-2 text-slate-700 dark:text-gray-400" hx-boost>
    <div id="messages-root"
         _="on clearMessage remove .hidden from me then add .hidden to #message-view-container"
         class="app-messages lg:block lg:mr-2 w-full lg:max-w-md
         [&::-webkit-scrollbar]:w-2
  [&::-webkit-scrollbar-track]:bg-gray-100
  [&::-webkit-scrollbar-thumb]:bg-gray-300
  dark:[&::-webkit-scrollbar-track]:bg-slate-700
  dark:[&::-webkit-scrollbar-thumb]:bg-slate-500
  overscroll-contain
  overflow-y-auto
  bg-light-background dark:bg-dark-background">
        <input
            hx-get="/mailbox/{{$mailbox}}/"
            hx-target="#messages-container"
            hx-select="#messages-container"
            hx-indicator=".htmx-indicator"
            hx-trigger="input changed delay:500ms, search"
            type="search"
            name="search"
            placeholder="Search messages"
            class="py-3 px-4 block w-full border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-dark-background dark:border-dark-border dark:text-dark-font-list dark:focus:ring-gray-600"
        >
        @fragment("messages-meta")
        <template id="messages-meta">
        </template>
        @endfragment
        @fragment("messages-list")
        <div id="messages-container">
            <ul
                id="message-list"
                class="flex flex-col justify-stretch pb-2 cursor-pointer" hx-sync="this:replace" hx-indicator="#message-loading-skeleton"
            >
            @foreach($messages->messages as $message)
            <li
                key="{{md5($message->path)}}"
                hx-target="#message-view-contents"
                hx-select="#message-view-content"
                _="init
                    log 'init first'
                    @if($message->path === $selected_message)
                    go to the top of me
                    @endif
                    on click
                        take .bg-light-hover-msg from .message-list-entry for me
                        take .dark:bg-dark-hover-list-office from .message-list-entry for me
                        add .hidden to #messages-root
                        remove .hidden from #message-view-container
                    end
                    on pop_over
                        log 'pop_over'
                    end
                    on htmx:beforeOnLoad
                        log 'beforeload'
                        take .bg-light-hover-msg  from .message-list-entry for me
                        take .dark:bg-dark-hover-list-office from .message-list-entry for me
                    end
                    on htmx:beforeSend
                        if #message-view-content then
                            remove #message-view-content
                        end
                        remove .hidden from #message-loading-skeleton
                    end
                    on dblclick
                        js window.open(
                            '{{route('mailbox.message-view', ['mailbox' => $mailbox->username, 'message' => Crypt::encryptString($message->path), 'compat' => true])}}',
                            'Viewing: {{addslashes($message->meta?->subject)}}',
                            'menubar=1,scrollbars=1,toolbar=1,width=600,height=450'
                        )
                    end
                    "

                @if($message->path === $selected_message)
                    hx-trigger="load, click"
                @endif
                @class([
                'message-list-entry border border-gray-200 dark:border-dark-border dark:text-dark-font-office ',
                'bg-light-hover-msg dark:bg-dark-hover-list-office' => $message->path === $selected_message,
                ])
                >
                    <a
                        hx-boost="true"
                        hx-swap="show:none"
                        hx-sync="closest li:drop"
                        href="{{route('mailbox.message-view', ['mailbox' => $mailbox->username, 'message' => Crypt::encryptString($message->path)])}}"
                        hx-push-url="false"
                        @class([
                       'inline-flex items-center h-18 gap-x-2 text-sm font-medium w-full',
                        ])
                    >
                    <div class="py-3 px-4 flex flex-col w-full">
                        <div class="flex justify-between content-start w-full items-baseline h-8 dark:text-dark-font">
                            <span class="truncate grow">{{ parse_display_rfc822($message->meta?->sender_envelope ?: $message->meta?->sender ?: '<>') }}</span>
                            <x-timestamp class="text-sky-900 dark:text-dark-text-sky w-30 flex-initial" :timestamp="$message->meta?->timestamp ?? now()"/>
                        </div>
                        <div class="flex flex-col justify-between w-full  text-sm h-10">
                            <span class="flex  text-light-subject-list  font-bolder dark:text-dark-font">
                                <x-heroicon-s-paper-clip @class(['w-4 h-4 text-gray-500 dark:text-dark-text-sky', 'hidden' => $message->meta?->attachment_count === 0]) />
                                {{ $message->meta?->subject ?? '--NO SUBJECT--' }}
                            </span>
                            <small class="py-1 text-xs font-normal truncate dark:text-dark-font ">{{$message->meta?->preview ?: ''}}</small>
                        </div>
                    </div>
                    </a>
                </li>
            @endforeach
            <li
                key="_loadmore"
                class="py-2 mx-auto"
            >

                <p @class([
                    'hidden' => filled($messages->continuation_token)
                ])>All caught up</p>
                <form
                    @class([
                        'hidden' => empty($messages->continuation_token)
                    ])
                    hx-get="{{route('mailbox.inbox', ['mailbox' => $mailbox])}}"
                    hx-trigger="intersect once delay:2s throttle:1s"
                    hx-select="#message-list>li"
                >
                    <ul class="space-y-1 htmx-indicator">
                        <li class="w-full h-4 bg-gray-200 rounded-full dark:bg-dark-background-700"></li>
                        <li class="w-full h-4 bg-gray-200 rounded-full dark:bg-dark-background-700"></li>
                        <li class="w-full h-4 bg-gray-200 rounded-full dark:bg-dark-background-700"></li>
                        <li class="w-full h-4 bg-gray-200 rounded-full dark:bg-dark-background-700"></li>
                    </ul>
                    <input type="hidden" id="continuation_token" name="continuation_token" value="{{encrypt($messages->continuation_token)}}">
                    <button type="submit">Load more</button>
                </form>
            </li>

        </ul>
        </div>
        @endfragment
    </div>
    <div id="message-view-container" class="
    flex-grow col-span-9 hidden lg:flex flex-col
    [&::-webkit-scrollbar]:w-2
  [&::-webkit-scrollbar-track]:bg-gray-100
  [&::-webkit-scrollbar-thumb]:bg-gray-300
  dark:[&::-webkit-scrollbar-track]:bg-dark-background-700
  dark:[&::-webkit-scrollbar-thumb]:bg-dark-background-500
  overscroll-contain
  overflow-y-auto
    ">

        @if(layout_effective() === \App\Support\SiteLayoutStyle::OFFICE)
        <header class="lg:hidden h-12 basis-4">
            <button
                id="message-list-return-button"
                _="
                   on click send clearMessage to #message-list
                "
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            >
                Return
            </button>
        </header>
        @endif
        <div id="message-view" class="flex-grow">
            <div id="message-loading-skeleton" class="mt-10 htmx-indicator hidden">
                <div class="flex animate-pulse">
                    <div class="flex-shrink-0">
                        <span class="w-12 h-12 block bg-gray-200 rounded-full dark:bg-dark-background-700"></span>
                    </div>

                    <div class="ms-4 mt-2 w-full">
                        <h3 class="h-4 bg-gray-200 rounded-full dark:bg-dark-background" style="width: 40%;"></h3>

                        <ul class="mt-5 space-y-3">
                            <li class="w-full h-4 bg-gray-200 rounded-full dark:bg-dark-background-700"></li>
                            <li class="w-full h-4 bg-gray-200 rounded-full dark:bg-dark-background-700"></li>
                        </ul>

                        <ul class="mt-10 space-y-3">
                            <li class="w-full h-4 bg-gray-200 rounded-full dark:bg-dark-background-700"></li>
                            <li class="w-full h-4 bg-gray-200 rounded-full dark:bg-dark-background-700"></li>
                            <li class="w-full h-4 bg-gray-200 rounded-full dark:bg-dark-background-700"></li>
                            <li class="w-full h-4 bg-gray-200 rounded-full dark:bg-dark-background-700"></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div id="message-view-contents"
                 _="on htmx:afterSwap add .hidden to #message-loading-skeleton"
                 class="ease-in-out app-messages overflow-y-hidden dark:bg--dark-background-100"
            >
                <div id="message-view-content" class="grid ease-in-out h-full w-full place-content-center">
                    <span class="-mt-12 peer">SELECT A MESSAGE</span>
                </div>
            </div>
        </div>
    </div>
</div>
