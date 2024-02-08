<?php

namespace App\Support\Configurations\Persistence;

use App\Contracts\InteractsWithPersistedSettings;
use App\Contracts\InteractsWithPersistedStorage;
use App\Contracts\InteractsWithResponse;
use App\Contracts\TransformsSavedData;
use App\Data\Configuration\Settings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Testing\Exceptions\InvalidArgumentException;
use Spatie\LaravelData\Contracts\DataObject;
use Spatie\LaravelData\Contracts\IncludeableData;
use Spatie\LaravelData\Data;

class DataStorageBrowserAdapter implements InteractsWithResponse, InteractsWithPersistedStorage
{
    private null|DataObject $pending = null;

    public function save(string $path, Data|DataObject $data): void
    {
        if($data instanceof TransformsSavedData) {
            $this->pending = $data->transformSave();
        } else {
            $this->pending = $data;
        }
    }

    public function restore(string $path, string $dataClass): ?DataObject
    {
        $data = request()->cookie(key: $path);
        return $data ? forward_static_call([$dataClass, 'from'], $data) : null;
    }

    public function location(): PersistenceLocation
    {
        return PersistenceLocation::BROWSER;
    }

    public function handleResponse(Response|RedirectResponse $response): Response|RedirectResponse
    {
        if($this->pending === null) {
            return $response;
        } else {
            $data = $this->pending->toJson();
        }

        $response->withCookie($this->cookieKey, $data);

        $this->pending = null;

        return $response;
    }
}
