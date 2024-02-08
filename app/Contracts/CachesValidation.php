<?php

namespace App\Contracts;

interface CachesValidation
{
    public function isValid(): bool;

    public function setValid(): void;

    public function invalidate(): void;
}
