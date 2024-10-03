<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_name' => $this->faker->word(),
            'project_sysname' => $this->faker->word(),
            'project_title' => $this->faker->word(),
            'project_desc' => $this->faker->words(5, true),
            'to_cd' => $this->faker->boolean,
        ];
    }
}
