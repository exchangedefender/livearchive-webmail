<?php

namespace App\Http\Middleware;

use App\Contracts\FirstTimeRun;
use Closure;
use Illuminate\Http\Request;
use Mauricius\LaravelHtmx\Http\HtmxRequest;
use Symfony\Component\HttpFoundation\Response;

class CheckSiteConfiguredMiddleware
{
    public function __construct(
        protected FirstTimeRun $firstTimeRun
    )
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!$this->firstTimeRun->initialized()) {
            info('first time run middleware triggered');
            $this->firstTimeRun->firstTimeRun();
            return redirect($request->fullUrl(), status: 308);
        }
        if ($this->should_skip($request, app(HtmxRequest::class))) {
            return $next($request);
        }
        if (! config_site()->wasConfigured()) {
//            dd('not configured redirect');
            return to_route('setup.index');
        } else {
            return $next($request);
        }
    }

    private function should_skip(Request $request, ?HtmxRequest $htmxRequest): bool
    {
        if (
            $request->routeIs(
                'setup.*', 'settings'
            )
        ) {
            return true;
        }

        return false;
    }
}
