<?php

namespace App\Rules\Data;

use Attribute;
use Spatie\LaravelData\Attributes\Validation\CustomValidationAttribute;
use Spatie\LaravelData\Support\Validation\ValidationPath;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class HostPort extends CustomValidationAttribute
{

    public function getRules(ValidationPath $path): array|object|string
    {
        return [new \App\Rules\HostPortRule()];
    }
}
