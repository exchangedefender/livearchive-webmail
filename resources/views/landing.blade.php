<x-layout>

    <div class="slide-it">
        <h1>Initial Content</h1>
        <button
            hx-get="/swap" hx-swap="innerHTML transition:true" hx-target="closest div"
            type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-gray-200 text-gray-500 hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none dark:border-gray-700 dark:text-gray-400 dark:hover:text-blue-500 dark:hover:border-blue-600 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
            Swap
        </button>
    </div>
</x-layout>

@push('styles')
    <style>
        @keyframes fade-in {
            from { opacity: 0; }
        }

        @keyframes fade-out {
            to { opacity: 0; }
        }

        @keyframes slide-from-right {
            from { transform: translateX(90px); }
        }

        @keyframes slide-to-left {
            to { transform: translateX(-90px); }
        }

        .slide-it {
            view-transition-name: slide-it;
        }

        ::view-transition-old(slide-it) {
            animation: 180ms cubic-bezier(0.4, 0, 1, 1) both fade-out,
            600ms cubic-bezier(0.4, 0, 0.2, 1) both slide-to-left;
        }
        ::view-transition-new(slide-it) {
            animation: 420ms cubic-bezier(0, 0, 0.2, 1) 90ms both fade-in,
            600ms cubic-bezier(0.4, 0, 0.2, 1) both slide-from-right;
        }
    </style>
@endpush
