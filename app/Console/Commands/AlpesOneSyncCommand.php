<?php

namespace App\Console\Commands;

use App\Models\Car;
use Illuminate\Console\Command;
use Illuminate\Http\Client\HttpClientException;
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

            if (empty($data)) {
                $this->warn('No data received from API');
                Log::warning('AlpesOne API returned empty data');
            }

            $carData = $data[0];
            $carData['external_id'] = $carData['id'];   // ou a chave correta da API
            unset($carData['id']);

            Car::query()->create($carData);

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
    }
}
