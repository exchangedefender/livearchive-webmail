<x-layout>
    <x-slot:top_nav_actions>
        <div></div>
    </x-slot:top_nav_actions>
    <x-onboarding-wizard>
        <x-slot:form>
            <x-onboarding-wizard-form step="Setup Site" description="Configure global site settings">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.25V18a2.25 2.25 0 0 0 2.25 2.25h13.5A2.25 2.25 0 0 0 21 18V8.25m-18 0V6a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 6v2.25m-18 0h18M5.25 6h.008v.008H5.25V6ZM7.5 6h.008v.008H7.5V6Zm2.25 0h.008v.008H9.75V6Z" />
                    </svg>
                </x-slot:icon>
                @csrf
                <x-error-message :show="$errors->isNotEmpty()"/>
                <div class="divide-y space-y-4 divide-gray-200 py-4">
                    <div class="text-base leading-6 text-gray-700 sm:text-lg sm:leading-7">
                        <x-form-select
                            required
                            name="timezone"
                            text="Timezone"
                            >
                                @foreach($timezones as $zone => $tzs)
                                    <optgroup label="{{$zone}}">
                                        <div class="divider"></div>
                                        @foreach($tzs as $tz)
                                            <option @selected(old('timezone') == $tz['value'] || settings()->siteSettings()->timezone === $tz['value']) key="{{strtolower($zone)}}-{{(string)str($tz['label'])->kebab()}}"
                                                    value="{{$tz['value']}}">{{$tz['label']}} ({{$tz['offset_string']}})</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach

                            </x-form-select>
                    </div>
                    <div class="flex flex-col-reverse text-light-font dark:text-dark-font">
                        <p class="text-sm text-gray-500 dark:text-gray-400"> Demo it!  <a href="javascript:;"  data-modal-target="office-modal" data-modal-toggle="office-modal" class="font-medium text-light-font-link hover:underline dark:text-dark-font-link">Office</a> or <a href="javascript:;"  data-modal-target="gmail-modal" data-modal-toggle="gmail-modal" class="font-medium text-light-font-link hover:underline dark:text-dark-font-link">Gmail</a> </p>
                        <span id="layout-error" class="ml-2 block text-sm text-light-error @error('layout') visible @else hidden @enderror">@error('layout'){{$message}}@enderror</span>
                        <div class="grid sm:grid-cols-2 gap-2">
                            <div class="has-[:checked]:bg-light-selected dark:has-[:checked]:bg-dark-hover-btn-blue/50 dark:has-[:checked]:hover:bg-dark-btn-blue/40 relative flex items-start p-3 block w-full bg-white border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-dark-neutral/10 dark:border-gray-700 dark:text-gray-400">
                                <div class="flex items-center h-5 mt-1">
                                    <input id="layout-gmail-radio" name="layout"
                                           @checked(old('layout') === \App\Support\SiteLayoutStyle::GMAIL->value || settings()->siteSettings()->layout === \App\Support\SiteLayoutStyle::GMAIL)
                                           value="{{\App\Support\SiteLayoutStyle::GMAIL->value}}"
                                           type="radio"
                                           class="w-0 invisible border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                           aria-describedby="layout-gmail-radio-description"
                                           @error('layout')
                                           aria-invalid="true"
                                           aria-errormessage="theme-error"
                                            @enderror
                                    >
                                </div>
                                <label for="layout-gmail-radio" class="ms-3">
                                    <span class="block text-sm font-semibold text-gray-800 dark:text-gray-300">Gmail</span>
                                    <span id="layout-gmail-radio-description" class="block text-sm text-gray-600 dark:text-gray-500">Single pane layout which transitions from list to view</span>
                                </label>
                            </div>
                            <div class="has-[:checked]:bg-light-selected dark:has-[:checked]:bg-dark-hover-btn-blue/50 dark:has-[:checked]:hover:bg-dark-btn-blue/40 relative flex items-start p-3 block w-full bg-white border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-dark-neutral/10 dark:border-gray-700 dark:text-gray-400">
                                <div class="flex items-center h-5 mt-1">
                                    <input @checked(old('layout') === \App\Support\SiteLayoutStyle::OFFICE->value || settings()->siteSettings()->layout === \App\Support\SiteLayoutStyle::OFFICE) id="layout-office-radio"
                                           name="layout" value="{{\App\Support\SiteLayoutStyle::OFFICE->value}}"
                                           type="radio"
                                           class="w-0 invisible border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                           aria-describedby="layout-office-radio-description">
                                </div>
                                <label for="layout-office-radio" class="ms-3">
                                    <span class="block text-sm font-semibold text-gray-800 dark:text-gray-300">Office</span>
                                    <span id="layout-office-radio-description" class="block text-sm text-gray-600 dark:text-gray-500">Dual pane layout with message list on the left and message view on the right </span>
                                </label>
                            </div>

                        </div>
                        <x-form-input-label>Theme</x-form-input-label>
                    </div>
                    @if(multitenant())
                        <input name="url" type="hidden" readonly value="{{settings()->siteSettings()->url ?? request()->getBaseUrl()}}">
                    @else
                    <x-form-input name="url" text="Url" value="{{old('url') ?? settings()->siteSettings()->url}}"/>
                    @endif
                </div>
            </x-onboarding-wizard-form>
        </x-slot:form>
    </x-onboarding-wizard>

    <!-- Main modal -->
    <div id="office-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <img src="{{ asset("storage/images/demo/la-office.png") }}" alt="" class="w-full object-cover rounded-t-xl" >
            </div>
        </div>
    </div>
    <div id="gmail-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <img src="{{ asset("storage/images/demo/la-gmail.png") }}" alt="" class="w-full object-cover rounded-t-xl" >
            </div>
        </div>
    </div>
</x-layout>

