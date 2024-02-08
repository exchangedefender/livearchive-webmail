<?php

namespace App\Contracts;

use Spatie\LaravelData\Contracts\DataObject;
use Spatie\LaravelData\Contracts\IncludeableData;

interface TransformsSavedData
{
    function transformSave(): DataObject;
}
