<?php

namespace App\Concerns;

trait CachesValidation
{
    public function isValid(): bool
    {
        return \Cache::get(self::class, fn () => false);
    }

    public function setValid(): void
    {
        \Cache::put(self::class, true);
    }

    public function invalidate(): void
    {
        \Cache::forget(self::class);
    }
}
