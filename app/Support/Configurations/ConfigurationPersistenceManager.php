<?php

namespace App\Support\Configurations;

use App\Contracts\InteractsWithPersistedSettings;
use App\Contracts\InteractsWithSiteConfigured;
use App\Data\Configuration\Settings;
use App\Support\Configurations\Persistence\PersistenceLocation;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ConfigurationPersistenceManager
//    implements HasBootImplementation
{
    public function __construct(
        protected InteractsWithPersistedSettings $persistence,
        protected InteractsWithSiteConfigured $siteConfigured,
    ) {

    }

    public function save(Settings $settings): void
    {
        $this->persistence->save($settings);
    }

    public function load(): Settings
    {
        return rescue(function(){
            $settings = $this->persistence->restore();
            if ($settings === null) {
                info('ConfigurationPersistenceManager failed to load persisted settings and will use the running defaults instead');
                $settings = Settings::current();
            } else {
                info('ConfigurationPersistenceManager restored settings', ['configured' => $this->siteConfigured]);
            }
            Storage::purge('s3');
            Storage::set('s3', $settings->bucket->toConfig());

            DB::purge('livearchive');
            DB::connectUsing('livearchive', $settings->database->toConfig());
            return $settings;
        }, function() {
            return rescue(function(){
                //failed to load, could be old settings that are missing options etc
                info('configuration load rescued');
                return Settings::current();
            }, function(){
                info('configuration load rescue failed');
                return Settings::from(Settings::empty());
            });
        });

    }

    public function location(): PersistenceLocation
    {
        return $this->persistence->location();
    }

    public function adapter(): InteractsWithPersistedSettings
    {
        return $this->persistence;
    }

    //    public static function boot(Application $app): static
    //    {
    //        return new self(persistence: $app->get(InteractsWithPersistentConfiguration::class));
    //    }
}
