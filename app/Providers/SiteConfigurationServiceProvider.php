<?php

namespace App\Providers;

use App\Contracts\ConfiguresSiteRendering;
use App\Contracts\FirstTimeRun;
use App\Contracts\InteractsWithBucketConfigured;
use App\Contracts\InteractsWithBucketSettings;
use App\Contracts\InteractsWithConfigured;
use App\Contracts\InteractsWithEnv;
use App\Contracts\InteractsWithLiveArchiveDatabaseConfigured;
use App\Contracts\InteractsWithLiveArchiveDatabaseSettings;
use App\Contracts\InteractsWithPersistedSettings;
use App\Contracts\InteractsWithPersistedStorage;
use App\Contracts\InteractsWithSiteConfigured;
use App\Contracts\InteractsWithSiteSettings;
use App\Contracts\OverridesSiteTheme;
use App\Data\Configuration\SiteConfiguredStateData;
use App\Support\Configurations\ConfigurationPersistenceManager;
use App\Support\Configurations\EnvManager;
use App\Support\Configurations\FirstTimeRunManager;
use App\Support\Configurations\NullEnvManager;
use App\Support\Configurations\Persistence\DataStorageBrowserAdapter;
use App\Support\Configurations\Persistence\DataStorageLocalFileAdapter;
use App\Support\Configurations\Persistence\PersistenceLocation;
use App\Support\Configurations\Persistence\SettingsBrowserAdapter;
use App\Support\Configurations\Persistence\SettingsLocalFileAdapter;
use App\Support\Configurations\SettingsManager;
use App\Support\SiteLayoutStyle;
use App\Support\SiteTemplate;
use App\Support\SiteTemplatingUserOverride;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class SiteConfigurationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //using scoped because settings include volatile settings

        $this->app->scoped(abstract: EnvManager::class, concrete: fn(Application $app) => new EnvManager(path: $app->environmentFilePath()));
        $this->app->scoped(abstract: SettingsManager::class, concrete: fn ($app) => SettingsManager::boot($app));
        $this->app->when(concrete: FirstTimeRunManager::class)
            ->needs(InteractsWithPersistedStorage::class)
            ->give('disk.global');
        $this->app->scoped(abstract: SiteConfiguredStateData::class, concrete: function(Application $app){
            $settings = $app->get(InteractsWithPersistedSettings::class)->restore();
            return $settings?->configured ?? SiteConfiguredStateData::from(SiteConfiguredStateData::empty());
        });
        $this->app->scoped(abstract: InteractsWithConfigured::class, concrete: SiteConfiguredStateData::class);
        $this->app->scoped(abstract: InteractsWithBucketConfigured::class, concrete: SiteConfiguredStateData::class);
        $this->app->scoped(abstract: InteractsWithLiveArchiveDatabaseConfigured::class, concrete: SiteConfiguredStateData::class);
        $this->app->scoped(abstract: InteractsWithSiteConfigured::class, concrete: SiteConfiguredStateData::class);
//        $this->app->bind(abstract: SiteConfigured::class, concrete: SiteConfigured::class);

        $this->app->bind(abstract: ConfigurationPersistenceManager::class, concrete: ConfigurationPersistenceManager::class);
        $this->app->bind(abstract: InteractsWithEnv::class, concrete: fn(Application $app) => boolval(config('livearchive.sync.env', true)) ? $app->get(EnvManager::class) :
        new NullEnvManager());
        $this->app->bind(abstract: InteractsWithSiteSettings::class, concrete: SettingsManager::class);
        $this->app->bind(abstract: InteractsWithBucketSettings::class, concrete: SettingsManager::class);
        $this->app->bind(abstract: InteractsWithLiveArchiveDatabaseSettings::class, concrete: SettingsManager::class);
        $this->app->bind(abstract: '$multitenant', concrete: fn(Application $app) => match($app->get(PersistenceLocation::class)){
            PersistenceLocation::BROWSER => true,
            PersistenceLocation::DISK => false
        });

        $this->app->singleton(abstract: FirstTimeRunManager::class, concrete: FirstTimeRunManager::class);
        $this->app->singleton(abstract: FirstTimeRun::class, concrete: FirstTimeRunManager::class);
        $this->app->singleton(abstract: PersistenceLocation::class, concrete: fn(Application $app) => match (config('livearchive.settings_adapter')) {
            PersistenceLocation::BROWSER => PersistenceLocation::BROWSER,
            PersistenceLocation::DISK => PersistenceLocation::DISK
        });

        $this->app->scoped(InteractsWithPersistedSettings::class, function (Application $app) {
            return match ($app->get(PersistenceLocation::class)) {
                PersistenceLocation::BROWSER => (new SettingsBrowserAdapter()),
                PersistenceLocation::DISK => new SettingsLocalFileAdapter(throw_if_missing: false),
            };
        });
        $this->app->scoped('disk.global', fn() => new DataStorageLocalFileAdapter(throw_if_missing: false));
        $this->app->scoped('disk.persisted', function (Application $app) {
            return match ($app->get(PersistenceLocation::class)) {
                PersistenceLocation::BROWSER => $app->make(DataStorageBrowserAdapter::class),
                PersistenceLocation::DISK => $app->make(DataStorageLocalFileAdapter::class),
            };
        });

        $this->app->bind(InteractsWithPersistedStorage::class, 'disk.persisted');

        $this->app->scoped(ConfiguresSiteRendering::class, function () {
            $style = settings()->siteSettings()->layout ?? config('livearchive.layout', SiteLayoutStyle::default());
            if (is_string($style)) {
                $style = SiteLayoutStyle::from($style);
            }
            return new SiteTemplate($style);
        });

        $this->app->bind(abstract: SiteTemplate::class, concrete: function ($app) {
            $layout = $app->get(ConfiguresSiteRendering::class);

            return new SiteTemplate(
                layout_style: $layout->siteLayoutStyle(),
            );
        });

        $this->app->bind(abstract: OverridesSiteTheme::class, concrete: SiteTemplatingUserOverride::class);
    }

    public function boot(): void
    {
        FirstTimeRunManager::boot();
    }
}
