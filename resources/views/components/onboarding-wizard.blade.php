
<div {{$attributes->class("min-h-dvh max-h-fit overflow-y-auto bg-light-background-800 dark:bg-dark-background flex flex-col justify-start pt-5")}}>
    <div class="relative mx-auto">
        <div class="flex flex-col gap-4 w-full max-w-lg bg-light-background dark:bg-dark-background-800  items-center mx-8 md:mx-0 shadow rounded-3xl px-10 pb-10 pt-5">
            <x-onboarding-stepper :model="config_site()"/>
            {{$form}}
        </div>
    </div>
</div>


