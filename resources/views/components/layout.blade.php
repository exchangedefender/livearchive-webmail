@props(['mailbox' => null, 'search' => null, 'filter' => null])
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LiveArchive</title>
    @stack('styles')
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <script src="https://unpkg.com/htmx.org@1.9.10" integrity="sha384-D1Kt99CQMDuVetoL1lrYwg5t+9QdHe7NLX/SoJYkXDFfX37iInKRy5xLSi8nO7UC" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/hyperscript.org@0.9.12"></script>
    <script defer src="https://unpkg.com/alpinejs@3.2.3/dist/cdn.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

    @stack('scripts')
</head>
<body class="bg-light-background dark:bg-dark-background dark:text-dark-font">
@if(isset($top_nav))
    {{ $top_nav }}
@else
<header class="app-header flex flex-wrap sm:justify-start sm:flex-nowrap w-full bg-light-background-header dark:bg-dark-background text-sm py-4 border-b border-gray-200">
    <nav class="w-full">
        <div class="px-3 lg:pl-3 h-full">
            <div class="flex items-center justify-between h-full">
                <div class="flex items-center justify-start">
                    <button id="toggleSidebarMobile" aria-expanded="true" aria-controls="sidebar" class="lg:hidden mr-2 text-gray-600 hover:text-gray-900 cursor-pointer p-2 hover:bg-gray-100 focus:bg-gray-100 focus:ring-2 focus:ring-gray-100 rounded">
                        <x-heroicon-c-bars-3-bottom-left id="toggleSidebarMobileHamburger" class="w-6 h-6"/>
                        <x-heroicon-c-bars-3-bottom-right id="toggleSidebarMobileClose" class="w-6 h-6 hidden"/>
                    </button>
                    <a href="/" class="text-xl font-bold flex items-center lg:ml-2.5">
                        <img src="{{asset('storage/images/xd_logo.png')}}" class="h-6 mr-2" alt="LiveArchive Logo">
                        <span class="self-center whitespace-nowrap text-light-header-font dark:text-dark-header-font">LiveArchive</span>
                    </a>
                    @if(isset($top_nav_actions))
                        {{ $top_nav_actions }}
                    @else
                        @inject('archiveDatabase', 'App\Contracts\ProvidesArchiveDatabase')
                        @if($archiveDatabase->hasArchiveDatabase() && filled($mailbox))
                        <div
                            class="hidden lg:block lg:pl-32"
                            id="topbar-search-root"
                        >
                            <label for="topbar-search" class="sr-only">Search</label>
                            <div
                                id="topbar-search-container" class="mt-1 relative w-64 lg:w-96"
                            >
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <x-heroicon-s-arrow-path id="search-progress" class="peer htmx-indicator hidden h-5 w-5 animate-[spin_2s_ease_infinite] text-gray-400" />
                                    <x-heroicon-o-magnifying-glass class="peer-[.htmx-request]:hidden w-5 h-5 text-gray-500"/>
                                </div>
                                <form action="{{route('mailbox.inbox', ['mailbox' => $mailbox], false)}}">
                                <input
                                    type="input"
                                    name="search"
                                    value="{{$search}}"
                                    id="topbar-search"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full pl-10 p-2.5"
                                    placeholder="Search"
                                    _="init
                                    on htmx:beforeRequest
                                        remove .hidden from #search-progress
                                    end
                                    on htmx:afterOnLoad
                                        add .hidden to #search-progress
                                        remove .hidden from #topbar-clear-search-root
                                    end
                                    "
                                    @if(filled($search))
                                        hx-push-url="true"
                                    @endif
                                    hx-get="{{route('mailbox.inbox', ['mailbox' => $mailbox], false)}}"
                                    hx-select-oob="#messages-meta,#messages-container,#topbar-search-actions,#topbar-clear-search-root"
                                    hx-trigger="keyup changed delay:500ms, search"
                                    hx-sync="this:replace, closest form:abort"
                                    hx-indicator="#search-progress"
                                    hx-boost
                                >
                                </form>
{{--                               @includeWhen(layout_effective() === \App\Support\SiteLayoutStyle::GMAIL, 'fragments.inbox-gmail-filter')--}}
                                @include('fragments.inbox-gmail-filter')
                            </div>
                        </div>
                       @endif
                    @endif

                </div>

                <div class="flex items-center justify-start">
                    <span id="topbar-status">
                            @if(isset($status))
                                {{$status}}
                            @endif
                    </span>
                </div>

                <div class="flex flex-col gap-5 mt-5 sm:flex-row sm:items-center sm:justify-end sm:mt-0 sm:ps-5">
                    @inject('siteTheme', 'App\Contracts\ConfiguresSiteRendering')
                    @inject('overrideTheme', 'App\Contracts\OverridesSiteTheme')
                    @if($overrideTheme->getSiteLayoutOverride() !== null)
                        <a href="{{request()->fullUrlWithQuery(['_layout' => '_clear'])}}" class="mx-4 text-red-900 hidden">clear override theme: {{$overrideTheme->getSiteLayoutOverride()->value}}</a>
                    @endif

                    <div class="relative inline-block">
                        <input @checked($overrideTheme->getSiteLayoutOverride() === \App\Support\SiteLayoutStyle::GMAIL || $siteTheme->siteLayoutStyle() === \App\Support\SiteLayoutStyle::GMAIL)
                               hx-get="{{request()->fullUrlWithoutQuery(['__layout'])}}"
                               hx-headers='{"X-Site-Layout-Override": "{{collect($siteTheme->getAvailableSiteLayouts($overrideTheme->getSiteLayoutOverride()??$siteTheme->siteLayoutStyle()))->first()}}"}'
                               hx-trigger="click"
                               hx-disabled-elt="this"
                               hx-target="body"
                               type="checkbox" id="hs-small-solid-switch-with-icons"
                               class="peer relative shrink-0 w-11 h-6 p-px bg-gray-700 border-transparent text-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:ring-gray-600 disabled:opacity-50 disabled:pointer-events-none checked:bg-none checked:text-gray-600 checked:border-gray-400 focus:checked:border-gray-600 dark:bg-dark-background-800 dark:border-sark-border dark:focus:ring-offset-gray-600
  before:inline-block before:w-5 before:h-5 before:bg-white  before:translate-x-0 checked:before:translate-x-full before:rounded-full before:shadow before:transform before:ring-0 before:transition before:ease-in-out before:duration-200 dark:before:bg-dark-background-400 dark:checked:before:bg-blue-200"
                        >
                        <label for="hs-small-solid-switch-with-icons" class="sr-only">switch</label>
                        <span class="peer-checked:text-gray-700 text-orange-600 w-5 h-5 absolute top-[3px] start-0.5 flex justify-center items-center pointer-events-none transition-colors ease-in-out duration-200">
                            <x-mdi-microsoft-office />
                        </span>
                        <span class="peer-checked:text-red-600 text-gray-500 w-5 h-5 absolute top-[3px] end-0.5 flex justify-center items-center pointer-events-none transition-colors ease-in-out duration-200">
                            <x-si-gmail />
                        </span>
                    </div>

                    <div class="flex flex-nowrap justify-end gap-4">
                        <button type="button" class="hs-dark-mode-active:hidden block hs-dark-mode group flex items-center text-light-icons hover:text-light-hover-icons font-medium dark:text-dark-icons dark:hover:text-dark-hover-icons" data-hs-theme-click-value="dark">
                            <x-heroicon-o-moon class="flex-shrink-0 w-5 h-5"/>
                        </button>
                        <button type="button" class="hs-dark-mode-active:block hidden hs-dark-mode group flex items-center text-light-icons hover:text-light-hover-icons font-medium dark:text-dark-icons dark:hover:text-dark-hover-icons" data-hs-theme-click-value="light">
                            <x-heroicon-o-sun class="flex-shrink-0 w-5 h-5"/>
                        </button>
                        <a href="{{route('settings')}}">
                            <button type="button" class="flex items-center text-light-icons hover:text-light-hover-icons font-medium dark:text-dark-icons dark:hover:text-dark-hover-icons">
                                <x-heroicon-o-cog-6-tooth class="flex-shrink-0 w-5 h-5"/>
                            </button>
                        </a>
                        @if(filled($mailbox))
                        <button
                            type="button"
                            class="text-light-icons  hover:text-light-hover-icons dark:text-dark-icons dark:hover:text-dark-hover-icons"
                            hx-post="{{route('mailbox.download', ['mailbox' => $mailbox])}}"
                            hx-target="#topbar-status"
                            hx-select="#download-status"

                        >
                            <x-heroicon-o-archive-box-arrow-down class="w-5 h-5"/>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
@endif
{{ $slot }}
@once
<script>
    // document.body.addEventListener('htmx:load', function(evt) {
    //     htmx.logger = function (elt, event, data) {
    //         if (console) {
    //             console.log(event, elt, data);
    //         }
    //     }
    // })
    (function() {
        const HSThemeAppearance = {
            init() {
                const defaultTheme = 'default'
                let theme = localStorage.getItem('hs_theme') || defaultTheme

                if (document.querySelector('html').classList.contains('dark')) return
                this.setAppearance(theme)
            },
            _resetStylesOnLoad() {
                const $resetStyles = document.createElement('style')
                $resetStyles.innerText = `*{transition: unset !important;}`
                $resetStyles.setAttribute('data-hs-appearance-onload-styles', '')
                document.head.appendChild($resetStyles)
                return $resetStyles
            },
            setAppearance(theme, saveInStore = true, dispatchEvent = true) {
                const $resetStylesEl = this._resetStylesOnLoad()

                if (saveInStore) {
                    localStorage.setItem('hs_theme', theme)
                }

                if (theme === 'auto') {
                    theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'default'
                }

                document.querySelector('html').classList.remove('dark')
                document.querySelector('html').classList.remove('default')
                document.querySelector('html').classList.remove('auto')

                document.querySelector('html').classList.add(this.getOriginalAppearance())

                setTimeout(() => {
                    $resetStylesEl.remove()
                })

                if (dispatchEvent) {
                    window.dispatchEvent(new CustomEvent('on-hs-appearance-change', {detail: theme}))
                }
            },
            getAppearance() {
                let theme = this.getOriginalAppearance()
                if (theme === 'auto') {
                    theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'default'
                }
                return theme
            },
            getOriginalAppearance() {
                const defaultTheme = 'default'
                return localStorage.getItem('hs_theme') || defaultTheme
            }
        }
        HSThemeAppearance.init()

        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            if (HSThemeAppearance.getOriginalAppearance() === 'auto') {
                HSThemeAppearance.setAppearance('auto', false)
            }
        })

        window.addEventListener('load', () => {
            const $clickableThemes = document.querySelectorAll('[data-hs-theme-click-value]')
            const $switchableThemes = document.querySelectorAll('[data-hs-theme-switch]')

            $clickableThemes.forEach($item => {
                $item.addEventListener('click', () => HSThemeAppearance.setAppearance($item.getAttribute('data-hs-theme-click-value'), true, $item))
            })

            $switchableThemes.forEach($item => {
                $item.addEventListener('change', (e) => {
                    HSThemeAppearance.setAppearance(e.target.checked ? 'dark' : 'default')
                })

                $item.checked = HSThemeAppearance.getAppearance() === 'dark'
            })

            window.addEventListener('on-hs-appearance-change', e => {
                $switchableThemes.forEach($item => {
                    $item.checked = e.detail === 'dark'
                })
            })






        })




        //attach csrf token to requests
        document.addEventListener('htmx:configRequest', function ({detail}) {
            detail['headers']['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content
        });

    })()


</script>
@endonce
<script>
    function printMsg(){
        var divContents = document.getElementById("print-header").innerHTML;
        // var iframe=document.getElementById('print-frame').innerHTML;
        // let frameObj = document.getElementById("print-header");
        // let frameContent = frameObj.contentWindow.document.body.innerHTML;

        const iframe=document.getElementById('print-frame');
        const tempWindow = window.open('', 'Print', 'height=600,width=800');
        const printHeader = document.getElementById('print-header').innerHTML;

        const newIframe = document.createElement('iframe');
        newIframe.src = iframe.src;

        newIframe.style = 'border: 0; width: 100%; height: 100%;';
        tempWindow.document.body.style = 'margin: 0;';


        //tempWindow.document.body.appendChild(printHeader);
        tempWindow.document.body.appendChild(newIframe);
        tempWindow.document.body.innerHTML = printContents;

        newIframe.onload = () => {
            tempWindow.print()
            tempWindow.close()
        }



        // const iframe=document.getElementById('print-frame');
        // const tempWindow = window.open('', 'Print', 'height=600,width=800');
        // const printHeader = document.getElementById('print-header').innerHTML;
        //
        // const newIframe = document.createElement('iframe');
        // newIframe.src = iframe.src;
        //
        // newIframe.style = 'border: 0; width: 100%; height: 100%;';
        // tempWindow.document.body.style = 'margin: 0;';
        //
        // tempWindow.document.body.appendChild(printHeader);
        // tempWindow.document.body.appendChild(newIframe);
        //
        // newIframe.onload = () => {
        //     tempWindow.print()
        //     tempWindow.close()
        // }


        // var printContents = document.getElementById('message-view-content').innerHTML;
        // var originalContents = document.body.innerHTML;
        //
        // document.body.innerHTML = printContents;
        //
        // window.print();

        // document.body.innerHTML = originalContents;
        // var frm = document.getElementById('message-view-content').contentWindow;
        // frm.focus();// focus on contentWindow is needed on some ie versions
        // frm.print();
        // return false;



    }
</script>
</body>
</html>
