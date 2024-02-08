<?php

namespace App\Http\Controllers\Setup;

use App\Data\Configuration\SiteSettingsData;
use App\Http\Controllers\Controller;
use App\Support\Configurations\SiteConfigured;
use Carbon\CarbonTimeZone;
use Mauricius\LaravelHtmx\Http\HtmxRequest;
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRedirect;

class SiteSetupController extends Controller
{
    public function __construct(
    ) {
    }

    public function index()
    {
        $timezones = collect(\DateTimeZone::listIdentifiers())->mapToGroups(function ($v, $k) {
            $parts = str($v)->split('/\//');
            $zone = (string) $parts->first();

            return [
                $zone => [
                    'label' => (string) str($parts->skip(1)->join('_'))->replace('_', ' '),
                    'value' => $v,
                    'offset_string' => (new CarbonTimeZone($v))->toOffsetName(),
                ],
            ];
        })->toArray();

        return view(view: 'setup-site', data: [
            'timezones' => $timezones,
        ]);
    }

    public function store(HtmxRequest $request, SiteSettingsData $settingsData)
    {
        settings()->updateSettings($settingsData);
        config_site()->markSiteConfigured();
        if ($request->isHtmxRequest()) {
            return new HtmxResponseClientRedirect(route('setup.bucket'));
        } else {
            return to_route('setup.bucket')
                ->with('message', 'Successfully updated site settings');
        }
    }
}
