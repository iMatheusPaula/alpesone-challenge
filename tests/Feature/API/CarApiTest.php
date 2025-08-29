<?php

use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('can get all cars without authentication', function () {
    Car::factory()->count(3)->create();

    $response = $this->getJson('/api/cars');

    expect($response)
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'external_id',
                    'brand',
                    'model',
                    'created_at',
                    'updated_at'
                ]
            ]
        ])
        ->assertJsonCount(3, 'data')
        ->assertJson([
            'success' => true
        ]);
});

test('can get a specific car without authentication', function () {
    $car = Car::factory()->create([
        'brand' => 'Toyota',
        'model' => 'Corolla'
    ]);

    $response = $this->getJson("/api/cars/{$car->id}");

    expect($response)
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'external_id',
                'brand',
                'model'
            ]
        ])
        ->assertJson([
            'success' => true,
            'data' => [
                'brand' => 'Toyota',
                'model' => 'Corolla'
            ]
        ]);
});

test('cannot create a car without authentication', function () {
    $carData = [
        'type' => 'Sedan',
        'brand' => 'Honda',
        'model' => 'Civic'
    ];

    $response = $this->postJson('/api/cars', $carData);

    expect($response)->assertStatus(401);

    $this->assertDatabaseMissing('cars', $carData);
});

test('can create a car with authentication', function () {
    Sanctum::actingAs(User::factory()->create());

    $carData = [
        'type' => 'Sedan',
        'brand' => 'Honda',
        'model' => 'Civic'
    ];

    $response = $this->postJson('/api/cars', $carData);

    expect($response)
        ->assertStatus(201)
        ->assertJson([
            'success' => true,
            'message' => 'Car created successfully',
            'data' => $carData
        ]);

    $this->assertDatabaseHas('cars', $carData);
});

test('cannot update a car without authentication', function () {
    $car = Car::factory()->create([
        'brand' => 'Toyota',
        'model' => 'Corolla'
    ]);

    $payload = [
        'brand' => 'Toyota',
        'model' => 'Camry',
        'type' => 'Sedan'
    ];

    $response = $this->putJson("/api/cars/{$car->id}", $payload);

    expect($response)->assertStatus(401);

    $this->assertDatabaseHas('cars', [
        'id' => $car->id,
        'model' => 'Corolla'
    ]);

    $this->assertDatabaseMissing('cars', [
        'id' => $car->id,
        'model' => 'Camry'
    ]);
});

test('can update a car with authentication', function () {
    Sanctum::actingAs(User::factory()->create());

    $car = Car::factory()->create([
        'brand' => 'Toyota',
        'model' => 'Corolla',
        'type' => 'Sedan'
    ]);

    $payload = [
        'brand' => 'Toyota',
        'model' => 'Camry',
        'type' => 'Sedan'
    ];

    $response = $this->putJson("/api/cars/{$car->id}", $payload);

    expect($response)
        ->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Car updated successfully',
            'data' => $payload
        ]);

    $this->assertDatabaseHas('cars', [
        'id' => $car->id,
        'model' => 'Camry'
    ]);
});

test('cannot delete a car without authentication', function () {
    $car = Car::factory()->create();

    $response = $this->deleteJson("/api/cars/{$car->id}");

    expect($response)->assertStatus(401);

    $this->assertDatabaseHas('cars', ['id' => $car->id]);
});

test('can delete a car with authentication', function () {
    Sanctum::actingAs(User::factory()->create());

    $car = Car::factory()->create();

    $response = $this->deleteJson("/api/cars/{$car->id}");

    expect($response)->assertStatus(204);

    $this->assertDatabaseMissing('cars', ['id' => $car->id]);
});

test('validation rules are enforced when creating a car', function () {
    Sanctum::actingAs(User::factory()->create());

    $payload = [
        'price' => 100000
    ];

    $response = $this->postJson('/api/cars', $payload);

    expect($response)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['type', 'brand', 'model']);
});

test('validation rules are enforced when updating a car', function () {
    Sanctum::actingAs(User::factory()->create());

    $car = Car::factory()->create();

    $payload = [
        'brand' => '',
        'model' => '',
        'type' => ''
    ];

    $response = $this->putJson("/api/cars/{$car->id}", $payload);

    expect($response)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['brand', 'model', 'type']);
});
