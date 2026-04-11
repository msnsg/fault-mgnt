<?php

namespace Database\Factories;

use App\Models\Fault;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Fault>
 */
class FaultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fault_reference' => 'FR-' . $this->faker->unique()->numerify('######'),
            'incident_title' => $this->faker->sentence,
            'category_id' => 1,
            'lat' => 1.1,
            'long' => 103.1,
            'incident_time' => now(),
        ];
    }
}
