<?php

use App\Models\Car;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(fn() => Cache::forget('alpes_one_sync_time'));

test('command successfully processes valid data from API', function () {
    $response = [
        [
            'id' => 'car123',
            'type' => 'Sedan',
            'brand' => 'Toyota',
            'model' => 'Corolla',
            'version' => 'XEi',
            'year' => ['model' => '2022', 'build' => '2021'],
            'optionals' => ['air', 'abs'],
            'doors' => '4',
            'board' => 'ABC1234',
            'chassi' => '123456789',
            'transmission' => 'Automatic',
            'km' => '10000',
            'description' => 'Test car description',
            'category' => 'Sedan',
            'url_car' => 'https://example.com/car123',
            'old_price' => 120000,
            'price' => 100000,
            'color' => 'Silver',
            'fuel' => 'Gasoline',
            'fotos' => ['https://example.com/photo1.jpg'],
            'sold' => false,
            'created' => '2025-02-27 15:53:57',
            'updated' => '2025-06-16 16:27:36',
        ]
    ];

    Http::fake(['https://hub.alpes.one/api/v1/integrator/export/*' => Http::response($response)]);

    $this->artisan('app:alpes-one-sync')->assertSuccessful();

    $this->assertDatabaseHas('cars', [
        'external_id' => 'car123',
        'brand' => 'Toyota',
        'model' => 'Corolla',
        'version' => 'XEi',
    ]);
});

test('command handles API errors gracefully', function () {
    Http::fake(['https://hub.alpes.one/api/v1/integrator/export/*' => Http::response('Server Error', 500)]);

    $this->artisan('app:alpes-one-sync')
        ->expectsOutput('Fetching data from AlpesOne API...')
        ->expectsOutputToContain('Error synchronizing data:')
        ->assertSuccessful();

    $this->assertDatabaseCount('cars', 0);
});

test('command updates existing cars with new data', function () {
    Car::factory()->create([
        'external_id' => 'car123',
        'brand' => 'Toyota',
        'model' => 'Corolla',
        'price' => 90000,
    ]);

    $response = [
        [
            'id' => 'car123',
            'brand' => 'Toyota',
            'model' => 'Corolla',
            'price' => 85000, // Price reduced
            'updated' => Carbon::now()->toDateTimeString(),

    ]
    ];

    Http::fake(['https://hub.alpes.one/api/v1/integrator/export/*' => Http::response($response)]);

    $this->artisan('app:alpes-one-sync')->assertSuccessful();

    $this->assertDatabaseHas('cars', [
        'external_id' => 'car123',
        'price' => 85000, // Should be updated
    ]);

    $this->assertDatabaseCount('cars', 1);
});

test('command removes cars no longer in API response', function () {
    Car::factory()->create([
        'external_id' => 'car123',
        'brand' => 'Toyota',
        'model' => 'Corolla',
    ]);

    Car::factory()->create([
        'external_id' => 'car456',
        'brand' => 'Honda',
        'model' => 'Civic',
    ]);

    Cache::put('alpes_one_sync_time', Carbon::now()->subHour(), now()->addDay());

    $apiData = [
        [
            'id' => 'car123',
            'brand' => 'Toyota',
            'model' => 'Corolla',
            'updated' => Carbon::now()->toDateTimeString(),
        ]
    ];

    Http::fake(['https://hub.alpes.one/api/v1/integrator/export/*' => Http::response($apiData)]);

    $this->artisan('app:alpes-one-sync')->assertSuccessful();

    $this->assertDatabaseCount('cars', 1);
    $this->assertDatabaseHas('cars', ['external_id' => 'car123']);
    $this->assertDatabaseMissing('cars', ['external_id' => 'car456']);
});

test('command correctly processes field mappings', function () {
    $response = [
        [
            'id' => 'car123',
            'type' => 'SUV',
            'brand' => 'Jeep',
            'model' => 'Compass',
            'version' => 'Limited',
            'year' => ['model' => '2023', 'build' => '2022'],
            'optionals' => ['leather', 'sunroof', 'camera'],
            'doors' => '5',
            'board' => 'XYZ7890',
            'chassi' => '987654321',
            'transmission' => 'Automatic',
            'km' => '5000',
            'description' => 'Luxury SUV with all options',
            'category' => 'SUV',
            'url_car' => 'https://example.com/suv123',
            'old_price' => 200000,
            'price' => 180000,
            'color' => 'Black',
            'fuel' => 'Diesel',
            'fotos' => ['https://example.com/photo1.jpg', 'https://example.com/photo2.jpg'],
            'sold' => false,
            'created' => '2025-02-27 15:53:57',
            'updated' => '2025-06-16 16:27:36'
        ]
    ];

    Http::fake(['https://hub.alpes.one/api/v1/integrator/export/*' => Http::response($response)]);

    $this->artisan('app:alpes-one-sync')->assertSuccessful();

    $this->assertDatabaseHas('cars', [
        'external_id' => 'car123',
        'type' => 'SUV',
        'brand' => 'Jeep',
        'model' => 'Compass',
        'version' => 'Limited',
        'model_year' => '2023',
        'build_year' => '2022',
        'doors' => '5',
        'board' => 'XYZ7890',
        'chassi' => '987654321',
        'transmission' => 'Automatic',
        'km' => '5000',
        'description' => 'Luxury SUV with all options',
        'category' => 'SUV',
        'url_car' => 'https://example.com/suv123',
        'old_price' => 200000,
        'price' => 180000,
        'color' => 'Black',
        'fuel' => 'Diesel',
        'sold' => 0,
        'created_at_source' => '2023-02-01 00:00:00',
        'updated_at_source' => '2023-02-02 00:00:00'
    ]);
});
