<?php

namespace App\Http\Middleware;

use App\Contracts\ConfiguresSiteRendering;
use App\Contracts\OverridesSiteTheme;
use App\Support\SiteLayoutStyle;
use App\Support\SiteTemplate;
use App\Support\SiteTemplatingUserOverride;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChangeLayoutMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->query('_layout') !== null || $request->hasHeader('X-Site-Layout-Override')) {
            $layout = $request->query('_layout') ?? $request->header('X-Site-Layout-Override');
            if (empty($layout) || $layout === '_clear') {
                return redirect(request()->fullUrlWithoutQuery(['_layout']), 308)
                    ->withCookie(cookie()->forget('_layout'));
            }
        } elseif ($request->hasCookie('_layout')) {
            $layout = $request->cookie('_layout');
        } else {
            return $next($request);
        }

        $use_layout = SiteLayoutStyle::tryFrom($layout);
        if ($use_layout !== null) {
            app()->bind(ConfiguresSiteRendering::class, fn () => new SiteTemplate($use_layout));
            app()->bind(OverridesSiteTheme::class, fn () => new SiteTemplatingUserOverride($use_layout));
            if ($request->cookie('_layout') != $layout) {
                return $next($request)
                    ->withCookie(cookie('_layout', $layout));
            }
        }

        return $next($request);
    }
}
