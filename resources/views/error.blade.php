<x-layout>
    <x-slot name="top_nav_actions">

    </x-slot>
    <div
        @if(isset($container_id))
            id="{{$container_id}}"
        @endif
        class="container mx-auto dark:bg-dark-bg-inside-msg dark:h-screen dark:px-2">
        <section>
            @if(layout_effective() === \App\Support\SiteLayoutStyle::GMAIL)
            <div class="flex justify-between items-center h-20 py-2">
                <button   id="message-list-return-button"
                          hx-get="{{route('mailbox.inbox', [...request()->all(), 'mailbox' => $mailbox])}}"
                          hx-push-url="true"
                          hx-target="body"  type="button" class="text-white bg-light-btn-blue hover:bg-light-hover-btn-blue focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:dark-btn-blue dark:hover:bg-dark-hover-btn-blue  dark:focus:ring-blue-800">

                    <svg class="w-3.5 h-3.5 me-2 text-white dark:text-dark-font" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"></path>
                    </svg>Return
                </button>
            </div>
            @endif

            <div class="lg:px-10 lg:py-24 md:py-20 md:px-44 px-4 py-24 items-center flex justify-center flex-col-reverse lg:flex-row md:gap-16">


                <div class="xl:pt-15 w-full xl:w-3/4 relative pb-12 lg:pb-0">

                    <div class="relative">
                        <div class="absolute">
                            <div class="pt-3">
                                <h6 class="block mb-4  text-6xl font-sans text-base antialiased font-semibold leading-relaxed tracking-normal text-red-500 uppercase">
                                    Error!
                                </h6>
                                <h1 class="my-2 text-gray-800 font-bold text-4xl">
                                    {{$issue}}
                                </h1>
                                <p class="my-2 text-gray-800 text-2xl pt-2 break-all"> {{$message}} </p>
{{--                                <button class="sm:w-full lg:w-auto my-2 border rounded md py-4 px-8 text-center bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-700 focus:ring-opacity-50">Take me there!</button>--}}
                            </div>
                        </div>
                        <div>
                            <img src="{{asset('storage/images/error/404.png')}}" />
                        </div>
                    </div>
                </div>
                <div>
                    <svg id="fi_7390085" viewBox="0 0 64 64" width="250" xmlns="http://www.w3.org/2000/svg" data-name="Layer 3"><path d="m60.407 22.791a4.031 4.031 0 0 1 1.593 3.209v33a3 3 0 0 1 -3 3h-54a3 3 0 0 1 -3-3v-33a4.031 4.031 0 0 1 1.593-3.209l26.607-20.191a2.974 2.974 0 0 1 1.8-.6 2.974 2.974 0 0 1 1.8.6z" fill="#f79b31"></path><path d="m60.407 22.791-26.607-20.191a2.975 2.975 0 0 0 -3.594 0l-22.376 16.977a4.015 4.015 0 0 0 -.83 2.423v33a3 3 0 0 0 3 3h36a2 2 0 0 1 0 4h13a3 3 0 0 0 3-3v-33a4.031 4.031 0 0 0 -1.593-3.209zm-29.407 30.209a12 12 0 1 1 12-12 12 12 0 0 1 -12 12z" fill="#fcb732"></path><path d="m32 41a3.8 3.8 0 0 0 1.87-.493l27.888-15.807a4.006 4.006 0 0 0 -1.351-1.913l-26.607-20.187a2.975 2.975 0 0 0 -3.594 0l-26.613 20.191a4.006 4.006 0 0 0 -1.351 1.909l27.888 15.8a3.8 3.8 0 0 0 1.87.5z" fill="#ec8329"></path><path d="m55 9v19.53l-21.13 11.98a3.813 3.813 0 0 1 -3.74 0l-21.13-11.98v-19.53a3 3 0 0 1 3-3h40a3 3 0 0 1 3 3z" fill="#ccc"></path><path d="m52 6h-40v16.385a9.746 9.746 0 0 0 4.858 8.431l13.272 7.694a3.813 3.813 0 0 0 3.74 0l21.13-11.98v-17.53a3 3 0 0 0 -3-3z" fill="#f2f2f2"></path><path d="m41 53h18v2h-18z" fill="#fff"></path><path d="m35 57h24v2h-24z" fill="#fff"></path><g fill="#ccc"><path d="m12 19.001h40v2h-40z"></path><path d="m12 22.999h40v2h-40z"></path><path d="m12 27h10v2h-10z"></path><path d="m17.999 11h28v2h-28z"></path><path d="m12 14.999h40v2h-40z"></path></g><circle cx="32" cy="40" fill="#e73844" r="12"></circle><path d="m32 28a11.93 11.93 0 0 0 -7.419 2.581 11.988 11.988 0 0 0 16.838 16.838 11.988 11.988 0 0 0 -9.419-19.419z" fill="#ff4d55"></path><path d="m39 36-3-3-4 4-4-4-3 3 4 4-4 4 3 3 4-4 4 4 3-3-4-4z" fill="#fff"></path></svg>
                </div>
            </div>

{{--            <h2 class="text-center text-2xl mt-5">{{$issue}}</h2>--}}
{{--            {{$message}}--}}
        </section>
    </div>
</x-layout>
