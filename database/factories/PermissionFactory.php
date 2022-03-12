<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Permission>
 */
class PermissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'       => $this->faker->unique()->words(4, true),
            'guard_name' => 'web',
            'route_name' => Str::slug($this->faker->unique()->words(4, true), '.'),
            'level'      => rand(1, 10),
            'group'      => $this->faker->domainWord,
        ];
    }
}
