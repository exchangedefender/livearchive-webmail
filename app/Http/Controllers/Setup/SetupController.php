<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Support\Configurations\SettingsManager;

class SetupController extends Controller
{
    public function __construct(
        protected SettingsManager $settingsManager,
    ) {
    }

    public function index()
    {
        $onboarding = config_site()->onboarding();

        $next = $onboarding->nextUnfinishedStep();
        $next ??= !config_site()->wasConfigured() ? $onboarding->steps()->first() : null;
        if ($onboarding->finished() && $next === null) {
            return to_route('home');
        } else {
            return redirect($next->link);
        }
    }

    public function settings()
    {
        return redirect(config_site()->onboarding()->steps()->first()->link);
    }
}
