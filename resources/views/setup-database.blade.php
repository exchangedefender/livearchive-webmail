<x-layout>
    <x-slot:top_nav_actions>
        <div></div>
    </x-slot:top_nav_actions>
    <x-onboarding-wizard>
        <x-slot:form>
            @error('connect', 'connection')
                <x-error-message :show="true" headline="Connection failure" message="{{$errors->connection->first('connect')}}"/>
            @else
            <x-error-message :show="$errors->isNotEmpty()"/>
            @enderror
            <x-onboarding-wizard-form
                    step="Setup Database"
                    description="Enhance livearchive with searching and filtering by enabling an archive database"
                    _="on formToggle(disable) if disable is true
                        add [@disabled] then remove .la-collapse-expanded from #database-form-items then add .la-collapse to #database-form-items
                        else remove [@disabled] then remove .la-collapse from #database-form-items then add .la-collapse-expanded to #database-form-items
                    "
            >
                <x-slot:actions>
                    <button
                            formaction="{{route('setup.database.disable')}}"
                            formmethod="post"
                            formnovalidate
                            type="submit"
                            class="nojs-visible bg-light-neutral flex justify-center items-center w-full text-white px-4 py-3 rounded-md focus:outline-none">
                        Skip database support
                    </button>
                </x-slot:actions>
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"/>
                    </svg>
                </x-slot:icon>
                @csrf
                <div class="flex flex-col-reverse text-light-font dark:text-dark-font">
                        <span id="database-support-error" class="ml-2 block text-sm text-light-error @error('disabled') visible @else hidden @enderror">@error('disabled'){{$message}}@enderror</span>
                        <div class="grid sm:grid-cols-2 gap-2"
                             _="on change from .form-toggle send formToggle(disable: event.target.id is 'database-support-disabled') to closest <form/>"
                        >
                            <div class="relative has-[:checked]:bg-light-selected dark:has-[:checked]:bg-dark-hover-btn-blue/50 dark:has-[:checked]:hover:bg-dark-btn-blue/40  block w-full bg-white border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-dark-neutral/10 dark:border-gray-700 dark:text-gray-400">
                                <div class="inset-0 h-full flex">
                                        <input id="database-support-enabled"
                                           type="radio"
                                           name="disabled"
                                           @checked(old('disabled') === '0' || old('disabled') === null && !settings()->databaseSettings()->disabled)
                                           value="0"
                                           class="h-0 w-0 form-toggle invisible border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                           aria-describedby="database-support-disabled-description"
                                        >
                                    <label id="database-support-disabled-description" for="database-support-enabled" class="flex-grow">
                                        <div class="px-4 py-2">
                                            <span class="block text-sm font-semibold text-gray-800 dark:text-gray-300">Enabled</span>
                                            <span id="layout-gmail-radio-description" class="block text-sm text-gray-600 dark:text-gray-500">Enable database support</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="relative has-[:checked]:bg-light-selected dark:has-[:checked]:bg-dark-hover-btn-blue/50 dark:has-[:checked]:hover:bg-dark-btn-blue/40  block w-full bg-white border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-dark-neutral/10 dark:border-gray-700 dark:text-gray-400">
                                <div class="inset-0 h-full flex">
                                    <input id="database-support-disabled"
                                           type="radio"
                                           name="disabled"
                                           @checked(old('disabled') === '1' || (old('disabled') === null && settings()->databaseSettings()->disabled))
                                           value="1"
                                           class="h-0 w-0 form-toggle invisible border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                           aria-describedby="database-support-disabled-description"
                                    >
                                    <label id="database-support-disabled-description" for="database-support-disabled" class="flex-grow">
                                        <div class="px-4 py-2">
                                            <span class="block text-sm font-semibold text-gray-800 dark:text-gray-300">Disabled</span>
                                            <span id="layout-office-radio-description" class="block text-sm text-gray-600 dark:text-gray-500">Continue without enabling database support</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <x-form-input-label>Database Support</x-form-input-label>
                    </div>
                <div id="database-form-items" class="transition-[grid-template-rows] duration-300 grid @if(old('disabled') === '1' || old('disabled') === null && settings()->databaseSettings()->disabled) la-collapse @else la-collapse-expanded @endif">
                    <div class="overflow-hidden">
                        <div class="py-8 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7">
                        <x-form-input class="required" name="host" text="Host" value="{{old('host') ?? settings()->databaseSettings()->host}}" placeholder="127.0.0.1:3306" >
                            <x-slot:description>
                                formatted as host:port
                            </x-slot:description>
                        </x-form-input>
                        <x-form-input class="required" name="database" text="Database" value="{{old('database') ?? settings()->databaseSettings()->database}}" />
                        <x-form-input name="username" text="Username" value="{{old('username') ?? settings()->databaseSettings()->username}}"/>
                        <div id="password-root"
                             hx-on::after-swap="new HSTogglePassword(document.querySelector('#password-input + button'))"
                        >
                            <x-form-secret-input
                                    name="password"
                                    text="Password"
                                    id="password-input"
                                    value="{{old('password')}}"

                            >
                            @if(filled(settings()->databaseSettings()->password) && request()->header('X-Update-Password') !== '1')
                                <x-slot:input>
                                    <input type="hidden" name="skipPasswordUpdate" value="1">
                                    <button
                                            hx-get="{{request()->url()}}"
                                            hx-headers="{{Js::encode(['X-Update-Password' => '1'])}}"
                                            hx-select="#password-root"
                                            hx-target="#password-root"
                                            class="bg-light-light dark:bg-transparent dark:text-dark-font dark:border-dark-border border border-light-btn-blue text-light-hover-btn-blue hover:bg-light-neutral hover:border-light-light hover:text-white flex justify-center items-center w-full px-4 py-3 rounded-md focus:outline-none"
                                    >Change password</button>
                                </x-slot:input>
                            @endif
                            </x-form-secret-input>
                        </div>
                    </div>
                    </div>
                </div>
            </x-onboarding-wizard-form>
        </x-slot:form>
    </x-onboarding-wizard>
</x-layout>

