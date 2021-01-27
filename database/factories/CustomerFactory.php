<?php

namespace Database\Factories;

use App\Models\Area;
use App\Models\ConnectionPoint;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $area = Area::inRandomOrder()->first();
        $connectionPoint = $area->connection_points()->inRandomOrder()->first();


        return [
            'user_id' => 1,
            'name' => $this->faker->name,
            'f_name' => $this->faker->name('male'),
            'm_name' => $this->faker->name('female'),
            'mobile' => $this->faker->unique()->numberBetween(1111111111, 9999999999),
            'nid' => $this->faker->numberBetween(11111111, 999999999),
            'status' => $this->faker->numberBetween(0, 2),
            'address' => $this->faker->address,
            'area_id' => $area->id,
            'connection_point_id' => $connectionPoint->id ?? 1,
        ];
    }
}
