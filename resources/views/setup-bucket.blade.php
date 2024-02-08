<x-layout>
    <x-slot:top_nav_actions>
        <div></div>
    </x-slot:top_nav_actions>
    <x-onboarding-wizard>
        <x-slot:form>
            <x-onboarding-wizard-form step="Setup Bucket" description="Connect livearchive with your archived messages in your object bucket">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                    </svg>
                </x-slot:icon>
                @csrf
                @error('connect', 'connection')
                <x-error-message :show="true" headline="Connection failure" message="{{$errors->connection->first('connect')}}"/>
                @else
                <x-error-message :show="$errors->isNotEmpty()"/>
                @enderror
                <div class="divide-y divide-gray-200">
                    <div class="py-8 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7">
                        <x-form-input name="bucket" text="Bucket Name" value="{{old('bucket') ?? settings()->bucketSettings()->bucket}}" required/>
                        <x-form-input name="region" text="Region" class="required">
                            <x-slot:input>
                                <div class="required peer relative has-[:focus]:outline-0 has-[:focus]:ring-0 has-[:focus]:border-1 has-[:focus]:border-gray-200">
                                    <input type="text" id="custom-region" name="custom_region"
                                           placeholder="custom region"
                                           required
                                           class="invisible [@media(scripting:none)]:visible transition-transform focus:ring-0 focus:border-inherit py-3 px-0 pr-0 ps-40 ps-0 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:z-10  disabled:opacity-50 disabled:pointer-events-none dark:bg-transparent dark:text-dark-font  text-light-font"
                                           _="
                                        init
                                            remove .ps-40 from me then add .ps-0 to me
                                            add .max-w-min to me then add  .min-w-min to me

                                        end
                                       "
                                    >
                                    <div class="absolute inset-y-0 start-0 flex items-center text-gray-500 ps-px">
                                        <label for="region" class="sr-only">AWS Region</label>
                                        <select id="region" name="region"
                                                required
                                                class="peer px-4 py-2 min-w-min max-w-min sm:text-sm border focus:ring-0 focus:border-gray-900 text-sm rounded-md focus:outline-none border-gray-300 dark:bg-transparent dark:text-dark-font  text-light-font"
                                                _="
                                                init
                                                    if @value is not 'custom'
                                                        add @@disabled to #custom-region
                                                    end
                                                end
                                                on change if event.target.value is 'custom'
                                                        add .max-w-min to me then remove @@disabled from #custom-region
                                                        remove .ps-0 from the previous <input/>
                                                        remove .max-w-min from the previous <input/>
                                                        remove .invisible from the previous <input/>
                                                        add .ps-40 to the previous <input/>
                                                        add .max-w-none to previous <input/> then focus() on the previous <input/>

                                                        else add @@disabled to #custom-region
                                                        set :region to event.target.value
                                                        log `switched region to ${:region}`
                                                        add .ps-0 to the previous <input/> then remove .ps-40 from the previous <input/>
                                                        remove .max-w-none from the previous <input/> then add .max-w-min to the previous <input/>
                                                        send formatEndpointForRegion(region: :region) to .listens-region
                                                    end"
                                        >
                                            @foreach($regions as $regionName => $regionRegions)
                                                <optgroup label="{{$regionName}}">
                                                    @foreach($regionRegions as $region)
                                                        @php
                                                            $identifier = $region->identifier;
                                                        @endphp
                                                        <option value="{{$identifier}}" @selected(old('region') === $identifier || settings()->bucketSettings()->region === $identifier)>{{$identifier}}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach

                                            <option value="custom" @selected(old('region') === 'custom')>Custom</option>

                                        </select>
                                    </div>
                                </div>
                            </x-slot:input>
                        </x-form-input>
                        <x-form-input
                            name="customEndpoint"
                            text="Bucket Endpoint"
                            value="{{old('customEndpoint') ?? settings()->bucketSettings()->customEndpoint}}"
                            placeholder="https://s3.{{old('region') ?? settings()->bucketSettings()->region}}.amazonaws.com"
                            class="listens-region"
                            _="
                            init
                                set :custom to false
                            end
                            on input if @value is not '' set :custom to true else set :custom to false end
                            on formatEndpointForRegion(region) set @placeholder to `https://s3.${region}.amazonaws.com` end

                            "
                        />
                        <x-form-input
                            name="accessKey"
                            text="Access Key"
                            value="{{old('accessKey') ?? settings()->bucketSettings()->accessKey}}"
                        />
                        <div id="sak-root"
                             hx-on::after-swap="new HSTogglePassword(document.querySelector('#sak-input + button'))"
                        >
                            <x-form-secret-input
                                    name="secretAccessKey"
                                    text="Secret Access Key"
                                    id="sak-input"
                                    value="{{old('secretAccessKey')}}"

                            >
                            @if(filled(settings()->bucketSettings()->secretAccessKey) && !$update_sak)
                                <x-slot:input>
                                    <input type="hidden" name="skipSakUpdate" value="1">
                                <button
                                        hx-get="{{request()->url()}}"
                                        hx-headers="{{Js::encode(['X-Update-Secret-Access-Key' => '1'])}}"
                                        hx-select="#sak-root"
                                        hx-target="#sak-root"
                                        hx-trigger="click"
                                        type="button"
                                        class="bg-light-light dark:bg-transparent dark:text-dark-font dark:border-dark-border border border-light-btn-blue text-light-hover-btn-blue hover:bg-light-neutral hover:border-light-light hover:text-white flex justify-center items-center w-full px-4 py-3 rounded-md focus:outline-none"
                                >Change key</button>
                                </x-slot:input>
                            @endif
                        </x-form-secret-input>
                        </div>
                    </div>
                </div>
            </x-onboarding-wizard-form>
        </x-slot:form>
    </x-onboarding-wizard>
</x-layout>


