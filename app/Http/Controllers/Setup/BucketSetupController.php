<?php

namespace App\Http\Controllers\Setup;

use App\Concerns\UsesSettings;
use App\Data\Configuration\BucketSettingsData;
use App\Data\Integrations\Aws\Region;
use App\Http\Controllers\Controller;
use App\Support\ServiceAvailabilityOutcome;
use Mauricius\LaravelHtmx\Http\HtmxRequest;
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRedirect;

class BucketSetupController extends Controller
{
    use UsesSettings;

    public function index()
    {
        $regions_array = app('aws_regions');
        $regions = collect($regions_array)->map(fn ($x) => Region::from($x))->mapToGroups(function ($item, $key) {
            return [$item->regionName => $item];
        });

        return view(view: 'setup-bucket', data: [
            'regions' => $regions,
            'regions_array' => $regions_array,
            'update_sak' => request()->header('X-Update-Secret-Access-Key') ?: false,
        ]);
    }

    public function store(HtmxRequest $request, BucketSettingsData $settings)
    {
        if(is_bool($settings->skipSakUpdate) && $settings->skipSakUpdate) {
            $settings = BucketSettingsData::from([
                ...$settings->except('secretAccessKey', 'endpoint')->toArray(),
                ...(settings()->bucketSettings()->only('secretAccessKey'))->toArray()
            ]);
        }

        $connection = app('archive.disk.connection', ['settings' => $settings]);
        $archive = app('archive.disk', ['filesystem' => $connection]);
        if($archive->checkArchiveFileSystemAvailability() !== ServiceAvailabilityOutcome::Ok) {
            info('connection failed not updating bucket', ['settings' => $settings]);
            return back()
                ->withInput()
                ->withErrors(['connect' => 'connection was unsuccessful'], 'connection');
        }
        settings()->updateBucketSettings($settings);
        config_site()->markObjectStoreConfigured();
        if ($request->isHtmxRequest()) {
            return new HtmxResponseClientRedirect(route('setup.database'));
        } else {
            return to_route('setup.database')
                ->with('message', 'Successfully updated bucket settings');
        }
    }
}
