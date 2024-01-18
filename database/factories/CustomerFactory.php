<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement([1, 2]);
        $shouldCode = fake()->randomElement([1, 2]);
        $shouldPhone = fake()->randomElement([1, 2]);
        return [
            'name' => $type == 1 ? fake()->name() : fake()->company(),
            'type' => $type == 1 ? 'Pessoa Física' : 'Pessoa Jurídica',
            'code' => $shouldCode == 1 ? ($type == 1 ? fake()->unique()->cpf(false) : fake()->unique()->cnpj(false)) : null,
            'phone_number' => $shouldPhone == 1 ? preg_replace('/[\s\(\)-]/', '', fake()->phoneNumber()) : null,
        ];
    }
}
