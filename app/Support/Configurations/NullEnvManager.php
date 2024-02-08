<?php

namespace App\Support\Configurations;

use App\Contracts\InteractsWithEnv;

class NullEnvManager implements InteractsWithEnv
{

    public function putEnvFile(array $data, bool $flush = false)
    {
        if($flush) {
            info('NullEnvManager does not update env file');
        }
    }

    public function updateEnvFile(string $key, string $value, bool $flush = false)
    {
        if($flush) {
            info('NullEnvManager does not update env file');
        }
    }

    public function flush(): void
    {
        info('NullEnvManager does not update env file. Set LIVEARCHIVE_ENV_SYNC to a true value or make sure to update the .env file manually');
    }
}
