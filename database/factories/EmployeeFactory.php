<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'name' => $this->faker->name,
            'role' => 1,
            'f_name' => $this->faker->name('male'),
            'm_name' => $this->faker->name('female'),
            'mobile' => $this->faker->numberBetween(11111111111, 99999999999),
            'nid' => $this->faker->numberBetween(1111111111, 9999999999),
            'address' => $this->faker->address,
            'username' => $this->faker->userName,
            'password' => Hash::make('test')
        ];
    }
}
