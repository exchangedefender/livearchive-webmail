<?php

namespace App\Data\Configuration;

use App\Contracts\TransformsSavedData;
use Spatie\LaravelData\Contracts\DataObject;
use Spatie\LaravelData\Contracts\IncludeableData;
use Spatie\LaravelData\Data;

class Settings extends Data implements TransformsSavedData
{
    public function __construct(
        public SiteSettingsData $site,
        public BucketSettingsData $bucket,
        public LiveArchiveDatabaseSettingsData $database,
        public SiteConfiguredStateData $configured,
        public bool $default,
    ) {
    }

    public static function current(): static
    {
        return new self(
            site: SiteSettingsData::current(),
            bucket: BucketSettingsData::current(),
            database: LiveArchiveDatabaseSettingsData::current(),
            configured: SiteConfiguredStateData::from(SiteConfiguredStateData::empty()),
            default: true,
        );
    }

    function transformSave(): DataObject
    {
       return $this->except('bucket.endpoint');
    }

}
