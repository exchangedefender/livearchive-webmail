<?php

use Mockery\MockInterface;

it('required input', fn () => expect($this->post(route('setup.database.store')))
    ->assertInvalid(['host', 'username', 'password', 'database'])
);

it('prohibits all other fields when uri is provided', fn () => expect($this->post(route('setup.database.store', [
    'uri' => 'mysql://username:password@hostname:port/dbname',
    'database' => 'test',
])))
    ->assertInvalid(['uri' => 'uri cannot be provided with connection parameters'])
);

it('redirects after updating bucket settings', function () {
    $this->mock(\App\Support\Configurations\EnvManager::class, fn (MockInterface $mock) => $mock->shouldReceive('putEnvFile')->once());

    expect(settings()
        ->databaseSettings()->database)
        ->toEqual('livearchive')
        ->and($this->post(route('setup.database.store'), [
            'host' => 'localhost:3306',
            'username' => 'username_test',
            'password' => 'password_test',
            'database' => 'database_test',
        ]))
        ->assertRedirectToRoute('setup.index')
        ->and(settings()
            ->databaseSettings())
        ->database
        ->toEqual('database_test');

});
