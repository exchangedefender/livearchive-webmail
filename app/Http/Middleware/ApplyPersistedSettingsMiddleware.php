<?php

namespace App\Http\Middleware;

use App\Contracts\InteractsWithPersistedSettings;
use App\Contracts\InteractsWithResponse;
use App\Support\Configurations\Persistence\PersistenceLocation;
use App\Support\Configurations\SettingsManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyPersistedSettingsMiddleware
{
    public function __construct(
        protected SettingsManager                $settingsManager,
        protected InteractsWithPersistedSettings $persistentConfiguration,

    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->settingsManager->settingsLocation() === PersistenceLocation::BROWSER) {
            $this->settingsManager->settings();
        }
        $this->settingsManager->settings();
        $response = $next($request);
        if ($this->persistentConfiguration instanceof InteractsWithResponse) {
            return $this->persistentConfiguration->handleResponse($response);
        } else {
            return $response;
        }

    }
}
