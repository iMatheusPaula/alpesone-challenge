<?php

use App\Models\User;

test('authenticated user can logout', function () {
    $user = User::factory()->create();

    $token = $user->createToken('auth_token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/api/logout');

    expect($response)
        ->assertStatus(200)
        ->assertJson(['message' => 'Logout realizado com sucesso'])
        ->and($user->tokens)->toHaveCount(0);
});

test('unauthenticated user cannot logout', function () {
    $response = $this->postJson('/api/logout');

    expect($response)
        ->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});
