<?php

namespace App\Support\Configurations;

use App\Contracts\FirstTimeRun;
use App\Contracts\HasBootImplementation;
use App\Contracts\InteractsWithPersistedStorage;
use App\Data\Configuration\InitializationData;
use Illuminate\Support\Carbon;

class FirstTimeRunManager implements FirstTimeRun, HasBootImplementation
{
    protected ?InitializationData $initializationData = null;

    public function __construct(
        protected InteractsWithPersistedStorage $persistedStorage
    )
    {

    }

    protected function restore(): void
    {
        info('FirstTimeRun restoring');
       $this->initializationData = $this->persistedStorage->restore(path: 'initialization.json', dataClass: InitializationData::class);
        info('FirstTimeRun restored', ['data' => $this->initializationData]);
    }

    protected function persist(): void
    {
        info('FirstTimeRun persist');
        $this->persistedStorage->save(path: 'initialization.json', data: $this->initializationData);
    }

    public function initialized(): bool
    {
        return $this->initializationData !== null;
    }

    public function firstTimeRun(): void {
        info('regenerating site key which will invalidate any previously encrypted data');
        \Artisan::call('key:generate');

        $this->initializationData = InitializationData::from(['when' => Carbon::now()]);
        $this->persist();
    }

    public static function boot(): void
    {
        app(abstract: static::class)->restore();
    }
}
