<?php

namespace App\Contracts;

interface InteractsWithEnv
{
    public function putEnvFile(array $data, bool $flush = false);

    public function updateEnvFile(string $key, string $value, bool $flush = false);

    public function flush(): void;
}
