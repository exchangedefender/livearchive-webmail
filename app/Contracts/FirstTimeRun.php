<?php

namespace App\Contracts;

interface FirstTimeRun
{
    public function initialized(): bool;
    public function firstTimeRun(): void;
}
