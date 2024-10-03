<?php

namespace Database\Factories;

use App\Models\Server;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Server>
 */
class ServerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'server_name' => $this->faker->words(2, true),
            'database_name' => $this->faker->words(2, true),
            'database_user' => $this->faker->userName,
            'database_password' => $this->faker->password,
            'ip_address' => $this->faker->ipv4,
            'port' => $this->faker->numberBetween(0, 65535),
            'disabled' => $this->faker->boolean,
            'production_server' => $this->faker->boolean,
            'update_required' => $this->faker->boolean,
        ];
    }
}
