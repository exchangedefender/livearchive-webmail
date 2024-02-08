<?php

namespace App\Data\Configuration;

use App\Contracts\CachesValidation;
use App\Rules\Data\AwsRegion;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\Validation\Accepted;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Regex;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\RequiredWith;
use Spatie\LaravelData\Attributes\Validation\Url;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class BucketSettingsData extends Data
{

    #[Computed]
    public string $endpoint;

    public function __construct(
        #[Required, Max(63), Regex('/(?!(^xn--|.+-s3alias$))^[a-z0-9][a-z0-9-]{1,61}[a-z0-9]$/')]
        public string $bucket,
        #[Required, AwsRegion]
        public string $region,
        #[Nullable, RequiredWith('secretAccessKey')]
        public ?string $accessKey,
        #[\SensitiveParameter]
        public ?string $secretAccessKey,
        #[Nullable, Url]
        public ?string $customEndpoint,
        #[Accepted]
        public Optional|bool $skipSakUpdate,
    ) {
        $this->endpoint = $this->customEndpoint ?? "https://s3.{$this->region}.amazonaws.com";
    }

    public static function messages(): array
    {
        return [
            'bucket.regex' => __('validation.bucket.regex.message'),
        ];
    }

    public static function current(): self
    {
        return self::from([
            'bucket' => strval(config('filesystems.disks.s3.bucket')),
            'region' => strval(config('filesystems.disks.s3.region')),
            'customEndpoint' => config('filesystems.disks.s3.endpoint') ?: null,
            'accessKey' => strval(config('filesystems.disks.s3.key')),
            'secretAccessKey' => strval(config('filesystems.disks.s3.secret')),
        ]);
    }

    public function toEnv(): array
    {
        return [
            'AWS_BUCKET' => $this->bucket,
            'AWS_ACCESS_KEY_ID' => $this->accessKey,
            'AWS_SECRET_ACCESS_KEY' => $this->secretAccessKey,
            'AWS_DEFAULT_REGION' => $this->region,
            'AWS_ENDPOINT' => $this->customEndpoint ?: '',
        ];
    }

    public function toConfig(): array
    {
        return [
            'driver' => 's3',
            'key' => $this->accessKey,
            'secret' => $this->secretAccessKey,
            'region' => $this->region,
            'bucket' => $this->bucket,
            'endpoint' => $this->customEndpoint,
            'use_path_style_endpoint' => true,
        ];
    }
}
