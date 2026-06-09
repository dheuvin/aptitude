<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'phone' => fake()->unique()->numerify('##########'),
            'role' => 'user',
            'password' => static::$password ??= Hash::make('password'),
        ];
    }
}
