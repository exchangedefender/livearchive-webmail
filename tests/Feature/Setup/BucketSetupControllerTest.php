<?php

use Mockery\MockInterface;

it('required input', fn () => expect($this->post(route('setup.bucket.store')))
    ->assertInvalid(['bucket', 'region'])
);

it('requires secretAccessKey when accessKey is provided', fn () => expect($this->post(route('setup.bucket.store', [
    'bucket' => 'test',
    'region' => 'us-east-1',
    'accessKey' => 'test',
    'secretAccessKey' => '',
])))
    ->assertInvalid(['secretAccessKey'])
);

it('redirects after updating bucket settings', function () {
    $this->mock(\App\Support\Configurations\EnvManager::class, fn (MockInterface $mock) => $mock->shouldReceive('putEnvFile')->once());

    expect(settings()
        ->bucketSettings()->endpoint)
        ->toEqual('https://s3.us-east-2.amazonaws.com')
        ->and($this->post(route('setup.bucket.store'), [
            'bucket' => 'test',
            'region' => 'us-east-1',
            'accessKey' => 'test',
            'secretAccessKey' => 'test',
        ]))
        ->assertRedirectToRoute('setup.index')
        ->and(settings()
            ->bucketSettings())
        ->endpoint
        ->toEqual('https://s3.us-east-1.amazonaws.com');

});
