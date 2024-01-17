<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'street' => fake()->streetName(),
            'number' => fake()->buildingNumber(),
            'neighborhood' => fake()->word(),
            'city' => fake()->randomElement(['Juazeiro do norte', 'Várzea Alegre', 'Crato', 'Barbalha']),
            'state' => 'Ceará',
            'zip_code' => fake()->randomNumber(8, true),
            'primary' => true,
            'get_customer_id' => null,
        ];
    }
}
