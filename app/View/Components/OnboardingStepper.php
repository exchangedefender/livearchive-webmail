<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Spatie\Onboard\OnboardingManager;
use Spatie\Onboard\OnboardingSteps;

class OnboardingStepper extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(protected \Spatie\Onboard\Concerns\Onboardable $model)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $nextUnfinished = $this->model->onboarding()->nextUnfinishedStep();
        $steps = $this->model->onboarding()->steps();
        $previousStep = null;
        $nextStep = null;
        $currentStep = null;
        $currentStepPosition = null;

        foreach ($this->model->onboarding()->steps() as $i => $c) {
            if(request()->is(str($c->attribute('link'))->trim('/'))) {
                $currentStep = $c;
                $previousStep = $steps->get($i - 1);
                $nextStep = $steps->get($i + 1);
                $currentStepPosition = $i;
                break;
            }
        }
        return view('components.onboarding-stepper', data: [
            ...compact('nextUnfinished', 'steps', 'nextStep', 'currentStep', 'previousStep', 'currentStepPosition')
        ]);
    }
}
