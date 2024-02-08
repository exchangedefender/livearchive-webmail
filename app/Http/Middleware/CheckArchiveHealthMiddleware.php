<?php

namespace App\Http\Middleware;

use App\Contracts\ChecksArchiveDatabaseSystemAvailability;
use App\Contracts\ChecksArchiveFileSystemAvailability;
use App\Contracts\FirstTimeRun;
use App\Support\ServiceAvailabilityOutcome;
use Closure;
use Illuminate\Http\Request;
use Mauricius\LaravelHtmx\Http\HtmxRequest;
use Symfony\Component\HttpFoundation\Response;

class CheckArchiveHealthMiddleware
{
    public function __construct(
        protected ChecksArchiveDatabaseSystemAvailability $archiveDatabaseSystemAvailability,
        protected ChecksArchiveFileSystemAvailability $archiveFileSystemAvailability,
    ) {

    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->should_skip($request, app(HtmxRequest::class))) {
            return $next($request);
        }
        $healthy = true;
        if (! match ($this->archiveDatabaseSystemAvailability->checkArchiveDatabaseAvailability()) {
            ServiceAvailabilityOutcome::Skipped,
            ServiceAvailabilityOutcome::Ok => true,
            ServiceAvailabilityOutcome::Unhealthy => false
        }) {
            $healthy = false;
            if (! $request->routeIs('health-archive.index')) {
                return to_route('health-archive.index')
                    ->with('reason', 'database');
            } else {
                session()->flash('reason', 'database');
            }
        } elseif (! match ($this->archiveFileSystemAvailability->checkArchiveFileSystemAvailability()) {
            ServiceAvailabilityOutcome::Ok => true,
            ServiceAvailabilityOutcome::Skipped,
            ServiceAvailabilityOutcome::Unhealthy => false
        }) {
            $healthy = false;
            if (! $request->routeIs('health-archive.index')) {
                return to_route('health-archive.index')
                    ->with('reason', 'file_system');
            } else {
                session()->flash('reason', 'file_system');
            }
        }

        if ($healthy && $request->routeIs('health-archive.index')) {
            //previous health issue has cleared, send home
            return to_route(config('archive-health-check.route.health_redirect_to'));
        }

        return $next($request);
    }

    private function should_skip(Request $request, ?HtmxRequest $htmxRequest): bool
    {
        if (
            $request->is(
                str(config('archive-health-check.route.prefix'))->finish('/*'),
            ) || //is health check route
            (! config('archive-health-check.exclude_htmx', false) && $htmxRequest?->isHtmxRequest()) || //htmx check
            $request->routeIs(
                config('archive-health-check.excluded_routes'),
            ) //excluded routes
        ) {
            return true;
        }

        return false;
    }
}
