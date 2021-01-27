<?php

namespace Database\Factories;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

class PackageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Package::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'name' => ucwords($this->faker->colorName . ' ' . $this->faker->word),
            'buying_price' => $this->faker->randomFloat(2, 100, 2500),
            'sale_price' => $this->faker->randomFloat(2, 150, 3000),
            'type' => $this->faker->numberBetween(1, 2)
        ];
    }
}
