<x-layout :mailbox="$mailbox" :search="$search" :filter="$filter">
    @inject('layout_template', 'App\Contracts\ConfiguresSiteRendering')
    @switch($layout_template->siteLayoutStyle())
        @case(\App\Support\SiteLayoutStyle::GMAIL)
            @include('fragments.inbox-gmail')
        @break
        @default
            @include('fragments.inbox-office')
        @break
    @endswitch

</x-layout>
