<?php

namespace App\Data\Integrations\Aws;

use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

class Region extends Data
{
    #[Computed]
    public readonly string $region;

    #[Computed]
    public readonly string $regionName;

    public function __construct(
        public readonly string $identifier,
    ) {
        if (preg_match(pattern: '/^([a-z]*-?[a-z]*)-(.+.)$/', subject: $this->identifier, matches: $matches)) {
            $this->region = $matches[1];
        } else {
            $this->region = 'custom';
        }
        $this->regionName = $this->regionName();
    }

    protected function regionName(): string
    {
        return match ($this->region) {
            'us' => 'United States',
            'af' => 'Africa',
            'ap' => 'Asia/Pacific',
            'ca' => 'Canada',
            'eu' => 'Europe',
            'il' => 'Israel',
            'me' => 'Middle East',
            'sa' => 'South America',
            'us-gov' => 'US GovCloud',
            default => 'Custom'
        };
    }

    public static function fromString(string $identifier): static
    {
        return new self($identifier);
    }
}
