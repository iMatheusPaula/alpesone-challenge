<?php

use App\Models\Car;

test('cars list is paginated', function () {
    Car::factory()->count(20)->create();

    $response = $this->getJson('/api/cars');

    expect($response)
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data',
            'pagination' => [
                'total',
                'per_page',
                'current_page',
                'last_page',
                'from',
                'to',
            ]
        ])
        ->assertJson([
            'success' => true,
            'pagination' => [
                'total' => 20,
                'per_page' => 15,
                'current_page' => 1,
                'last_page' => 2,
            ]
        ])
        ->assertJsonCount(15, 'data');
});

test('can navigate to second page of cars', function () {
    Car::factory()->count(20)->create();

    $response = $this->getJson('/api/cars?page=2');

    expect($response)
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data',
            'pagination' => [
                'total',
                'per_page',
                'current_page',
                'last_page',
                'from',
                'to',
            ]
        ])
        ->assertJson([
            'success' => true,
            'pagination' => [
                'total' => 20,
                'per_page' => 15,
                'current_page' => 2,
                'last_page' => 2,
            ]
        ])
        ->assertJsonCount(5, 'data');
});

test('empty page returns empty data array with pagination info', function () {
    Car::factory()->count(5)->create();

    $response = $this->getJson('/api/cars?page=2');

    expect($response)
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data',
            'pagination'
        ])
        ->assertJson([
            'success' => true,
            'data' => [],
            'pagination' => [
                'total' => 5,
                'per_page' => 15,
                'current_page' => 2,
                'last_page' => 1,
            ]
        ])
        ->assertJsonCount(0, 'data');
});
