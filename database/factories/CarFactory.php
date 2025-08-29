<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Car::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'external_id' => $this->faker->uuid(),
            'type' => $this->faker->randomElement(['Sedan', 'SUV', 'Hatchback', 'Pickup', 'Convertible']),
            'brand' => $this->faker->randomElement(['Toyota', 'Honda', 'Ford', 'BMW', 'Mercedes', 'Volkswagen']),
            'model' => $this->faker->word(),
            'version' => $this->faker->randomElement(['Basic', 'Premium', 'Sport', 'Luxury', null]),
            'model_year' => (string)$this->faker->numberBetween(2015, 2025),
            'build_year' => (string)$this->faker->numberBetween(2015, 2025),
            'optionals' => $this->faker->randomElements(
                ['Air Conditioning', 'Power Steering', 'ABS', 'Airbags', 'Sunroof', 'Leather Seats'],
                $this->faker->numberBetween(0, 4)
            ),
            'doors' => (string)$this->faker->numberBetween(2, 5),
            'board' => strtoupper($this->faker->bothify('???####')),
            'chassi' => strtoupper($this->faker->bothify('??########')),
            'transmission' => $this->faker->randomElement(['Manual', 'Automatic', 'Semi-Automatic']),
            'km' => (string)$this->faker->numberBetween(0, 100000),
            'description' => $this->faker->paragraph(),
            'category' => $this->faker->randomElement(['New', 'Used', 'Certified']),
            'url_car' => $this->faker->url(),
            'old_price' => $this->faker->optional(0.3)->numberBetween(50000, 200000),
            'price' => $this->faker->numberBetween(30000, 150000),
            'color' => $this->faker->colorName(),
            'fuel' => $this->faker->randomElement(['Gasoline', 'Diesel', 'Electric', 'Hybrid', 'Ethanol']),
            'photos' => $this->faker->randomElements([
                $this->faker->imageUrl(),
                $this->faker->imageUrl(),
                $this->faker->imageUrl(),
                $this->faker->imageUrl()
            ], $this->faker->numberBetween(0, 3)),
            'sold' => $this->faker->boolean(20),
            'created_at_source' => $this->faker->dateTimeBetween('-1 year', '-1 month'),
            'updated_at_source' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * Indicate that the car is sold.
     *
     * @return static
     */
    public function sold(): static
    {
        return $this->state(fn(array $attributes) => [
            'sold' => true,
        ]);
    }

    /**
     * Indicate that the car is available (not sold).
     *
     * @return static
     */
    public function available(): static
    {
        return $this->state(fn(array $attributes) => [
            'sold' => false,
        ]);
    }
}
