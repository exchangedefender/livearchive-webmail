<?php

namespace App\Support\Configurations\Persistence;

use App\Contracts\InteractsWithPersistedSettings;
use App\Contracts\InteractsWithResponse;
use App\Contracts\TransformsSavedData;
use App\Data\Configuration\Settings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Spatie\LaravelData\Contracts\IncludeableData;

class SettingsBrowserAdapter implements InteractsWithPersistedSettings, InteractsWithResponse
{
    private null|Settings|IncludeableData $pendingSettings = null;
    protected array $except = [];

    public function __construct(
        protected readonly string $cookieKey = 'livearchive-settings',
    )
    {
        if(empty($this->cookieKey)) {
            throw new \InvalidArgumentException(self::class . " cookieKey cannot be empty");
        }
    }

    public function except(string ...$except): self
    {
        $this->except = $except;
        return $this;
    }


    public function save(Settings $settings): void
    {
        $this->pendingSettings = $settings->transformSave();
    }

    public function restore(): ?Settings
    {
        try {
            $settings = request()->cookie(key: $this->cookieKey);
            return $settings ? Settings::from($settings) : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function location(): PersistenceLocation
    {
        return PersistenceLocation::BROWSER;
    }

    public function handleResponse(Response|RedirectResponse $response): Response|RedirectResponse
    {
        if($this->pendingSettings === null) {
            return $response;
        } else {
            $settings = $this->pendingSettings->toJson();
        }

        $response->withCookie($this->cookieKey, $settings);

        $this->pendingSettings = null;

        return $response;
    }
}
