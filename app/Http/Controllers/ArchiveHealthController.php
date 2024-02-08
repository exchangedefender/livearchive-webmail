<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArchiveHealthController extends Controller
{
    public function index(Request $request)
    {
        $reason = session('reason', 'unknown reason');
        $suggestions = ['Check your configuration settings'];
        $issue = 'Unknown error occurred';
        switch ($reason) {
            case 'database':
                $issue = sprintf('Failed to connect to the LiveArchive backend database on %s', config('database.connections.livearchive.host') ?? config('database.connections.livearchive.url'));
                $suggestions[] = 'Check the <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-lg text-red-500 ">LIVEARCHIVE_DB_HOST</span> environment variable';
                $suggestions[] = 'Check the <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-lg text-red-500">LIVEARCHIVE_DB_DATABASE</span> environment variable';
                $suggestions[] = 'Check the <apan class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-lg text-red-500">LIVEARCHIVE_DB_USERNAME</apan> environment variable';
                break;
            case 'file_system':
                $issue = sprintf('Failed to connect to the LiveArchive file system bucket "%s"', config('filesystems.disks.s3.bucket'));
                $customEndpoint = config('filesystems.disks.s3.endpoint');
                if (filled($customEndpoint)) {
                    $suggestions[] = sprintf('check the status of the custom S3 backend: %s', $customEndpoint);
                }
                if (filled(config('filesystems.disks.s3.key'))) {
                    $suggestions[] = 'Check the <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-lg text-red-500">AWS_ACCESS_KEY_ID</span> environment variable';
                }
                if (filled(config('filesystems.disks.s3.secret'))) {
                    $suggestions[] = 'Check the <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-lg text-red-500">AWS_SECRET_ACCESS_KEY</span> environment variable';
                }
                $suggestions[] = 'Check the <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-lg text-red-500">AWS_BUCKET</span> environment variable';
                $suggestions[] = 'Check the <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-lg text-red-500">AWS_DEFAULT_REGION</span> environment variable';
                break;
        }

        return view('health-issue', [
            'reason' => $reason,
            'suggestions' => $suggestions,
            'issue' => $issue,
        ]);
    }
}
