<?php

namespace App\Console\Commands;

use App\Models\Car;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AlpesOneSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:alpes-one-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncs data from AlpesOne API and updates the database';

    /**
     * API URL for data export.
     *
     * @var string
     */
    protected string $apiUrl = 'https://hub.alpes.one/api/v1/integrator/export/1902';


    /**
     * Cache key for storing the last sync time.
     *
     * @var string
     */
    protected string $cacheKey = 'alpes_one_sync_time';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Fetching data from AlpesOne API...');

        try {
            $response = Http::get($this->apiUrl);

            if (!$response->successful()) {
                throw new HttpClientException($response->body(), $response->status());
            }

            $data = $response->json();

            $this->processData($data);

            $this->info('AlpesOne data synchronization completed successfully');
        } catch (\Exception $e) {
            $this->error('Error synchronizing data: ' . $e->getMessage());
            Log::error('AlpesOne sync error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Process and store data from the API
     */
    protected function processData(array $data): void
    {
        $lastSyncTime = Cache::get($this->cacheKey);
        $currentSyncTime = now();

        $apiExternalIds = [];

        $this->info('Processing cars data from API...');

        foreach ($data as $carData) {
            $apiExternalIds[] = $carData['id'];

            // Map API data to database columns
            $mappedData = $this->map($carData);

            $isNewerThanLastSync = $lastSyncTime === null
                || Carbon::parse($carData['updated'] ?? null)->gt($lastSyncTime)
                || Carbon::parse($carData['created'] ?? null)->gt($lastSyncTime);

            if ($isNewerThanLastSync) {
                Car::query()->updateOrCreate(
                    ['external_id' => $carData['id']],
                    $mappedData
                );

                $this->info("Car data updated or created. ID: {$carData['id']}");
            }
        }

        // Remove cars that are no longer available in API
        if ($lastSyncTime) {
            $deletedCars = Car::query()
                ->whereNotIn('external_id', $apiExternalIds)
                ->get();

            foreach ($deletedCars as $deletedCar) {
                $this->info("Deleting car: {$deletedCar->brand} {$deletedCar->model} (ID: {$deletedCar->external_id})");
                $deletedCar->delete();
            }

            $this->info("Deleted {$deletedCars->count()} cars that are no longer available in API");
        }

        Cache::put($this->cacheKey, $currentSyncTime, now()->addDay());

        $this->info('Car data processing completed successfully');
    }

    /**
     * Map API data to database columns
     *
     * @param array $car
     * @return array
     */
    private function map(array $car): array
    {
        return [
            'external_id' => $car['id'],
            'type' => $car['type'] ?? null,
            'brand' => $car['brand'] ?? null,
            'model' => $car['model'] ?? null,
            'version' => $car['version'] ?? null,
            'model_year' => $car['year']['model'] ?? null,
            'build_year' => $car['year']['build'] ?? null,
            'optionals' => $car['optionals'] ?? [],
            'doors' => $car['doors'] ?? null,
            'board' => $car['board'] ?? null,
            'chassi' => $car['chassi'] ?? null,
            'transmission' => $car['transmission'] ?? null,
            'km' => $car['km'] ?? null,
            'description' => $car['description'] ?? null,
            'category' => $car['category'] ?? null,
            'url_car' => $car['url_car'] ?? null,
            'old_price' => $car['old_price'] ?? null,
            'price' => $car['price'] ?? null,
            'color' => $car['color'] ?? null,
            'fuel' => $car['fuel'] ?? null,
            'photos' => $car['fotos'] ?? [],
            'sold' => $car['sold'] ?? false,
            'created_at_source' => $car['created'] ?? null,
            'updated_at_source' => $car['updated'] ?? null,
        ];
    }
}
