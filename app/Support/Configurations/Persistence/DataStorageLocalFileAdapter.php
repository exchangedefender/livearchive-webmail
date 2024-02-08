<?php

namespace App\Support\Configurations\Persistence;

use App\Contracts\InteractsWithPersistedSettings;
use App\Contracts\InteractsWithPersistedStorage;
use App\Contracts\TransformsSavedData;
use App\Data\Configuration\Settings;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelData\Contracts\DataObject;

class DataStorageLocalFileAdapter implements InteractsWithPersistedStorage
{

    public function __construct(
        protected string $basePath = 'settings',
        protected bool $throw_if_missing = true,
    ) {

    }

    protected function filePath(string $path)
    {
        return str($this->basePath ?: 'settings')->finish('/')->append($path)->toString();
    }

    public function save(string $path, DataObject $data): void
    {
        if($data instanceof TransformsSavedData) {
            $data = $data->transformSave();
        }
        Storage::put($this->filePath($path), $data->toJson(JSON_PRETTY_PRINT));
    }

    /**
     * @param string $path
     * @param string $dataClass
     * @return DataObject|null
     * @throws \Throwable
     */
    public function restore(string $path, string $dataClass): ?DataObject
    {
        $fp = $this->filePath($path);
        if (! Storage::exists($fp)) {
            throw_if($this->throw_if_missing, FileNotFoundException::class, "Data not found at path {$fp}.");
            return null;
        }
        $json = Storage::get($fp);

        return forward_static_call([$dataClass, 'from'], $json);
    }

    public function location(): PersistenceLocation
    {
        return PersistenceLocation::DISK;
    }
}
