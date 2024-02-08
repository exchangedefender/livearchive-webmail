<?php

namespace App\Support\Configurations\Persistence;

use App\Contracts\InteractsWithPersistedSettings;
use App\Contracts\TransformsSavedData;
use App\Data\Configuration\Settings;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;

class SettingsLocalFileAdapter implements InteractsWithPersistedSettings
{
    private const FILE_NAME = 'configuration.json';

    public readonly string $filePath;

    public function __construct(
        protected ?string $basePath = null,
        protected bool $throw_if_missing = true,
    ) {
        $this->filePath = str($this->basePath ?: 'settings')->finish('/')->append(self::FILE_NAME)->toString();
    }

    public function save(Settings $settings): void
    {
        Storage::put($this->filePath, $settings->transformSave()->toJson(JSON_PRETTY_PRINT));
    }

    /**
     * @throws FileNotFoundException
     */
    public function restore(): ?Settings
    {
        if (! Storage::exists($this->filePath)) {
            throw_if($this->throw_if_missing, FileNotFoundException::class, "Settings not found at path {$this->filePath}.");
            return null;
        }
        $json = Storage::get($this->filePath);

        return Settings::from($json);
    }

    public function location(): PersistenceLocation
    {
        return PersistenceLocation::DISK;
    }
}
