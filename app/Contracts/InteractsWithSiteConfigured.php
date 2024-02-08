<?php

namespace App\Contracts;

interface InteractsWithSiteConfigured
{
    public function siteConfigured(): bool;
    public function markSiteConfigured(): void;
    public function resetSiteConfigured(): void;

}
