<ul class="relative flex flex-col md:flex-row gap-2">
    @foreach($steps as $i => $step)
        <!-- Item -->
        @php
            $completed = $i <= $currentStepPosition || $step->complete();
            $available = $i <= $currentStepPosition || $step->complete() || ($currentStep->complete() && $i === $currentStepPosition + 1);
        @endphp
        <li class="flex flex-col md:flex-row md:items-center gap-x-2 shrink basis-0 flex-1 group @if($available && $i !== $currentStepPosition) cursor-pointer @endif">
            <a
                    @if($available && $i !== $currentStepPosition)
                    href="{{$step->attribute('link')}}"
                    @endif
            >
                <div class="min-w-[28px] min-h-[28px] inline-flex items-center text-xs align-middle grow md:grow-0">
                    <span
                            @class([
                            "w-7 h-7 flex justify-center items-center flex-shrink-0 rounded-full font-medium",
                            "bg-green-500 text-white dark:bg-green-700 dark:text-white" => $step !== $currentStep && $completed ,
                            "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-white" => $step !== $currentStep && !$completed,
                            "bg-light-btn-blue text-white dark:bg-dark-btn-blue dark:text-white" => $step === $currentStep ,
                            ])>
                        @if($step !== $currentStep && $completed)
                            <x-heroicon-o-check-circle class="w-5 h-5"/>
                        @else
                        {{$i + 1}}
                        @endif
                    </span>
                    <span class="ms-2 block grow md:grow-0 text-sm font-medium @if($step === $currentStep) text-light-font dark:text-dark-font @else text-light-neutral dark:text-dark-neutral @endif">
                                        {{$step->attribute('title')}}
                                    </span>
                </div>
            </a>
            <div class="mt-2 w-px h-4 md:mt-0 ms-3.5 md:ms-0 md:w-full md:h-px md:flex-1 bg-gray-200 group-last:hidden dark:bg-gray-700"></div>
        </li>
        <!-- End Item -->
    @endforeach


</ul>
