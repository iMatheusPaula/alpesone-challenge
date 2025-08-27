<?php

use App\Models\User;

test('can create a user in the database', function () {
    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ];

    $user = User::query()->create($userData);

    expect($user)
        ->toBeInstanceOf(User::class)
        ->and($user->name)->toBe('Test User')
        ->and($user->email)->toBe('test@example.com');
});
