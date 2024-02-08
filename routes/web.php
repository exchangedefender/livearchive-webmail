<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/_', 'App\Http\Controllers\FirstTimeRunController');

Route::redirect('/', '/mailbox')->name('home');

Route::get('/swap', function () {
    return view('swap');
});
Route::get('/settings','App\Http\Controllers\Setup\SetupController@settings')->name('settings');
Route::prefix('/setup')
    ->name('setup.')
    ->group(function () {
        Route::get('/test', function () {
            app(\App\Contracts\InteractsWithPersistedSettings::class)->save(settings()->settings());

            return response('OK');
        });
        //TODO get rid of /bucket /database paths or adjust middleware to check if its the current step if in progress
        Route::get('/', 'App\Http\Controllers\Setup\SetupController@index')->name('index');
        Route::get('/site', 'App\Http\Controllers\Setup\SiteSetupController@index')->name('site');
        Route::post('/site', 'App\Http\Controllers\Setup\SiteSetupController@store')
            ->middleware(middleware: \App\Http\Middleware\ApplyPendingSettingsUpdatesMiddleware::class)
            ->name('site.store');

        Route::get('/bucket', 'App\Http\Controllers\Setup\BucketSetupController@index')->name('bucket');
        Route::post('/bucket', 'App\Http\Controllers\Setup\BucketSetupController@store')
            ->middleware(middleware: \App\Http\Middleware\ApplyPendingSettingsUpdatesMiddleware::class)
            ->name('bucket.store');

        Route::get('/database', 'App\Http\Controllers\Setup\DatabaseSetupController@index')->name('database');
        Route::post('/database/disable', 'App\Http\Controllers\Setup\DatabaseSetupController@disable')
            ->name('database.disable');
        Route::post('/database', 'App\Http\Controllers\Setup\DatabaseSetupController@store')
            ->middleware(middleware: \App\Http\Middleware\ApplyPendingSettingsUpdatesMiddleware::class)
            ->name('database.store');
    });

Route::prefix('/health')
    ->group(function () {
        Route::get('/', 'App\Http\Controllers\ArchiveHealthController@index')->name('health-archive.index');
    });

Route::prefix('/mailbox')
    ->group(function () {
        Route::get('/', 'App\Http\Controllers\ArchiveController@index')
            ->name('mailbox.list');

        Route::addRoute(['GET', 'POST'], '/{mailbox}/download', 'App\Http\Controllers\ArchiveInboxController@download')
            ->where('mailbox', '^[\w\d\!#%&\'\*\+\-\/=\?\^\_\{\|\}\~]+@[\w\d\-_\.]+$')
            ->name('mailbox.download');

        Route::get('/{mailbox}/{message}/view', 'App\Http\Controllers\ArchiveInboxController@view')
            ->where('mailbox', '^[\w\d\!#%&\'\*\+\-\/=\?\^\_\{\|\}\~]+@[\w\d\-_\.]+$')
            ->name('mailbox.message-view');

        Route::get('/{mailbox}/{message}/body', 'App\Http\Controllers\ArchiveInboxController@render')
            ->where('mailbox', '^[\w\d\!#%&\'\*\+\-\/=\?\^\_\{\|\}\~]+@[\w\d\-_\.]+$')
            ->name('mailbox.message-body');

        Route::get('/{mailbox}/{message}/download', 'App\Http\Controllers\ArchiveInboxController@downloadMessage')
            ->where('mailbox', '^[\w\d\!#%&\'\*\+\-\/=\?\^\_\{\|\}\~]+@[\w\d\-_\.]+$')
            ->name('mailbox.message-download');

        Route::get('/{mailbox}/{message}/{attachment}/download', 'App\Http\Controllers\ArchiveInboxController@downloadAttachment')
            ->where('mailbox', '^[\w\d\!#%&\'\*\+\-\/=\?\^\_\{\|\}\~]+@[\w\d\-_\.]+$')
            ->whereNumber('attachment')
            ->name('mailbox.attachment-download');

        Route::addRoute(['GET', 'POST'], '/{mailbox}/search', 'App\Http\Controllers\ArchiveInboxController@search')
            ->where('mailbox', '^[\w\d\!#%&\'\*\+\-\/=\?\^\_\{\|\}\~]+@[\w\d\-_\.]+$')
            ->name('mailbox.message-search');

        Route::get('/{mailbox}/{message?}', 'App\Http\Controllers\ArchiveInboxController@index')
            ->where('mailbox', '^[\w\d\!#%&\'\*\+\-\/=\?\^\_\{\|\}\~]+@[\w\d\-_\.]+$')
            ->name('mailbox.inbox');
    });
