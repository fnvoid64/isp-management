<?php

namespace Database\Factories;

use App\Models\Area;
use App\Models\ConnectionPoint;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConnectionPointFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ConnectionPoint::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'area_id' => Area::inRandomOrder()->first(),
            'name' => '#' . $this->faker->numerify()
        ];
    }
}
