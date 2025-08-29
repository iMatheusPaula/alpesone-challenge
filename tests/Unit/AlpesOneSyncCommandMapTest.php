<?php

use App\Console\Commands\AlpesOneSyncCommand;

test('map function correctly maps car data from API to database format', function () {
    $command = new AlpesOneSyncCommand();

    $reflector = new ReflectionClass($command);
    $method = $reflector->getMethod('map');
    $method->setAccessible(true);

    $carData = [
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
        'created' => '2023-01-01 00:00:00',
        'updated' => '2023-01-02 00:00:00',
    ];

    $mappedData = $method->invoke($command, $carData);

    expect($mappedData)->toBe([
        'external_id' => 'car123',
        'type' => 'Sedan',
        'brand' => 'Toyota',
        'model' => 'Corolla',
        'version' => 'XEi',
        'model_year' => '2022',
        'build_year' => '2021',
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
        'photos' => ['https://example.com/photo1.jpg'],
        'sold' => false,
        'created_at_source' => '2023-01-01 00:00:00',
        'updated_at_source' => '2023-01-02 00:00:00',
    ]);
});

test('map function handles empty year data', function () {
    $command = new AlpesOneSyncCommand();

    $reflector = new ReflectionClass($command);
    $method = $reflector->getMethod('map');
    $method->setAccessible(true);

    $carData = [
        'id' => 'car789',
        'year' => [],
    ];

    $mappedData = $method->invoke($command, $carData);

    expect($mappedData)->toMatchArray([
        'external_id' => 'car789',
        'model_year' => null,
        'build_year' => null,
    ]);
});
