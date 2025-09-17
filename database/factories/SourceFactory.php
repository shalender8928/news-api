<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Source;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Source>
 */
class SourceFactory extends Factory
{
    protected $model = Source::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $apiName = $this->faker->randomElement([
            'NewsAPI',
            'The Guardian',
            'New York Times',
            'BBC News'
        ]);

        return [
            'key'      => Str::slug($apiName) . '-' . $this->faker->unique()->word(),
            'title'    => $this->faker->company(),
            'api_name' => $apiName,
            'meta'     => json_encode([
                'description' => $this->faker->sentence(),
                'country'     => $this->faker->countryCode(),
            ]),
        ];
    }
}
