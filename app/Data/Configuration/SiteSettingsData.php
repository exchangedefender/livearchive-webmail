<?php

namespace App\Data\Configuration;

use App\Contracts\CachesValidation;
use App\Contracts\InteractsWithPersistedStorage;
use App\Support\SiteLayoutStyle;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Timezone;
use Spatie\LaravelData\Attributes\Validation\Url;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

class SiteSettingsData extends Data
{
    public function __construct(
        #[WithCast(EnumCast::class), Required, Enum(SiteLayoutStyle::class)]
        public SiteLayoutStyle $layout,
        #[Required, Timezone]
        public string $timezone,
        #[Required, Url]
        public string $url,
    ) {
    }

    public static function current(): self
    {
        return self::from([
            'layout' => config('livearchive.layout', SiteLayoutStyle::default())->value,
            'url' => strval(config('app.url')),
            'timezone' => strval(config('app.local_timezone')),
        ]);
    }

    public function toEnv(): array
    {
        return [
            'LIVEARCHIVE_LAYOUT' => $this->layout->value,
            'APP_URL' => $this->url,
            'APP_TIMEZONE' => $this->timezone,
        ];
    }
//
//    public function isValid(): bool
//    {
//        app(InteractsWithPersistedStorage::class)
//    }
//
//    public function setValid(): void
//    {
//        \Cache::put(self::class, true);
//    }
//
//    public function invalidate(): void
//    {
//        \Cache::forget(self::class);
//    }
}
