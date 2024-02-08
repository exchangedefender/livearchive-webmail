<?php

namespace App\Providers;

use App\Contracts\ImportExportMailboxContent;
use App\Contracts\ListsMailboxes;
use App\Contracts\RendersMailAttachments;
use App\Contracts\RendersMailMessage;
use App\Support\ArchiveFileSystem;
use App\Support\Configurations\SiteConfigured;
use App\Support\EmailMessageRenderer;
use Illuminate\Support\ServiceProvider;
use Spatie\Onboard\Facades\Onboard;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        Onboard::addStep('Configure site', SiteConfigured::class)
            ->link('/setup/site')
            ->cta('Complete')
//            ->excludeIf(fn (SiteConfigured $siteConfiguration) => $siteConfiguration->wasConfigured())
            ->completeIf(fn (SiteConfigured $siteConfiguration) => $siteConfiguration->wasSiteConfigured());

        Onboard::addStep('Configure object store', SiteConfigured::class)
            ->link('/setup/bucket')
            ->cta('Complete')
//            ->excludeIf(fn (SiteConfigured $siteConfiguration) => $siteConfiguration->wasConfigured())
            ->completeIf(fn (SiteConfigured $siteConfiguration) => $siteConfiguration->wasObjectStoreConfigured());

        Onboard::addStep('Configure database', SiteConfigured::class)
            ->link('/setup/database')
            ->cta('Complete')
//            ->excludeIf(fn (SiteConfigured $siteConfiguration) => $siteConfiguration->wasConfigured())
            ->completeIf(fn (SiteConfigured $siteConfiguration) => $siteConfiguration->wasDatabaseConfigured());

        $this->app->singleton('aws_regions', fn () => [
            'us-east-1', 'us-east-2',
            'us-west-1', 'us-west-2',

            'ca-central-1', 'ca-west-1',

            'eu-central-1', 'eu-central-2',
            'eu-west-1', 'eu-west-2', 'eu-west-3',
            'eu-south-1', 'eu-south-2',
            'eu-north-1',

            'ap-east-1',
            'ap-south-1', 'ap-south-2',
            'ap-southeast-1', 'ap-southeast-2', 'ap-southeast-3', 'ap-southeast-4',
            'ap-northeast-1', 'ap-northeast-2', 'ap-northeast-3',

            'il-central-1', 'me-south-1', 'me-central-1', 'sa-east-1', 'af-south-1',

            'us-gov-east-1', 'us-gov-west-1',
        ]);

        $this->app->bind('message_renderer', function () {
            return new EmailMessageRenderer();
        });
        $this->app->bind(RendersMailMessage::class, 'message_renderer');
        $this->app->bind(RendersMailAttachments::class, 'message_renderer');
        $this->app->bind(ImportExportMailboxContent::class, ArchiveFileSystem::class);
        $this->app->bind(ListsMailboxes::class, ArchiveFileSystem::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
