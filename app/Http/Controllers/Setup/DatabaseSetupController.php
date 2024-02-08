<?php

namespace App\Http\Controllers\Setup;

use App\Concerns\UsesSettings;
use App\Data\Configuration\LiveArchiveDatabaseSettingsData;
use App\Http\Controllers\Controller;
use App\Support\ServiceAvailabilityOutcome;
use Mauricius\LaravelHtmx\Http\HtmxRequest;
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRedirect;

class DatabaseSetupController extends Controller
{
    use UsesSettings;

    public function index()
    {
        return view(view: 'setup-database');
    }

    public function store(HtmxRequest $request, LiveArchiveDatabaseSettingsData $settings)
    {
        if(is_bool($settings->skipPasswordUpdate) && $settings->skipPasswordUpdate) {
            $settings = LiveArchiveDatabaseSettingsData::from([
                ...$settings->except('password')->toArray(),
                ...(settings()->databaseSettings()->only('password'))->toArray()
            ]);
        }
        if(!$settings->disabled) {
            $connection = app('archive.db.connection', ['settings' => $settings]);
            $archive = app('archive.db', ['connection' => $connection]);
            if ($archive->checkArchiveDatabaseAvailability() !== ServiceAvailabilityOutcome::Ok) {
                info('connection failed not updating database', ['settings' => $settings]);
                return back()
                    ->withInput()
                    ->withErrors(['connect' => 'connection was unsuccessful'], 'connection');
            }
        }
        settings()->updateDatabaseSettings($settings);
        config_site()->markDatabaseConfigured();
        if ($request->isHtmxRequest()) {
            return new HtmxResponseClientRedirect(route('home'));
        } else {
            return to_route('home')
                ->with('message', 'Successfully updated database settings');
        }

    }

    public function disable(HtmxRequest $request)
    {
        $settings = settings()->databaseSettings();
        $settings->disabled = true;
        settings()->updateDatabaseSettings($settings);
        config_site()->markDatabaseConfigured();
        if ($request->isHtmxRequest()) {
            return new HtmxResponseClientRedirect(route('home'));
        } else {
            return to_route('home')
                ->with('message', 'Successfully disabled database');
        }
    }
}
