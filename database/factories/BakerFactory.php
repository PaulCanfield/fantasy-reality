<?php

namespace Database\Factories;

use App\Season;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Baker;

class BakerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Baker::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'season_id' => Season::factory()->create()
        ];
    }
}
