<?php

namespace App\Support\Configurations;

use App\Contracts\InteractsWithBucketSettings;
use App\Contracts\InteractsWithEnv;
use App\Contracts\InteractsWithLiveArchiveDatabaseSettings;
use App\Contracts\InteractsWithSiteSettings;
use App\Data\Configuration\BucketSettingsData;
use App\Data\Configuration\LiveArchiveDatabaseSettingsData;
use App\Data\Configuration\Settings;
use App\Data\Configuration\SiteSettingsData;
use App\Support\Configurations\Persistence\PersistenceLocation;
use Illuminate\Contracts\Foundation\Application;

class SettingsManager implements
    InteractsWithSiteSettings,
    InteractsWithBucketSettings,
    InteractsWithLiveArchiveDatabaseSettings
{
    protected ?Settings $settings = null;
    protected readonly bool $updateEnvFile;

    public function __construct(
        protected EnvManager $envManager,
        protected readonly ConfigurationPersistenceManager $persistenceManager,
        PersistenceLocation $persistenceLocation,
    ) {
        $this->updateEnvFile = match($persistenceLocation) {
            PersistenceLocation::BROWSER => false,
            PersistenceLocation::DISK => true,
        };
    }

    public function settingsLocation(): PersistenceLocation
    {
        return $this->persistenceManager->location();
    }

    public function settings(): Settings
    {
        return $this->settings ??= $this->persistenceManager->load();
    }

    public function save(): void
    {
        $this->persistenceManager->save($this->settings());
    }

    public function siteSettings(): SiteSettingsData
    {
        return $this->settings()->site;
    }

    public function updateSettings(SiteSettingsData $settings): void
    {
        $this->settings()->site = $settings;
        $this->settings()->configured->markSiteConfigured();
        if($this->updateEnvFile) {
            $this->envManager->putEnvFile(
                $settings->toEnv(),
            );
        }
        $this->save();
    }

    public function bucketSettings(): BucketSettingsData
    {
        return $this->settings()->bucket;
    }

    public function updateBucketSettings(BucketSettingsData $settings): void
    {
        if(is_bool($settings->skipSakUpdate) && $settings->skipSakUpdate) {
            $settings = BucketSettingsData::from([
                ...$settings->except('secretAccessKey', 'endpoint')->toArray(),
                ...($this->settings()->bucket->only('secretAccessKey'))->toArray()
                ]);
        }
        $this->settings()->bucket = $settings;
        $this->settings()->configured->markBucketConfigured();
        if($this->updateEnvFile) {
            $this->envManager->putEnvFile(
                $settings->toEnv(),
            );
        }
        $this->save();
    }

    public function databaseSettings(): LiveArchiveDatabaseSettingsData
    {
        return $this->settings()->database;
    }

    public function updateDatabaseSettings(LiveArchiveDatabaseSettingsData $settings): void
    {
        if(is_bool($settings->skipPasswordUpdate) && $settings->skipPasswordUpdate) {
            $settings = LiveArchiveDatabaseSettingsData::from([
                ...$settings->except('password')->toArray(),
                ...($this->settings()->database->only('password'))->toArray()
            ]);
        }
        $this->settings()->database = $settings;
        $this->settings()->configured->markDatabaseConfigured();

        if($this->updateEnvFile) {
            $this->envManager->putEnvFile(
                $settings->toEnv(),
            );
        }

        $this->save();
    }

    public static function boot(Application $app): static
    {
        return new self(
            envManager: $app->get(InteractsWithEnv::class),
            persistenceManager: $app->get(ConfigurationPersistenceManager::class),
            persistenceLocation: $app->get(PersistenceLocation::class),
        );
    }
}
