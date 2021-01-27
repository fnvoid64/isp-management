<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\ConnectionPoint;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Pranto',
            'email' => 'pranto@softmight.com',
            'password' => Hash::make(1),
            'pin' => 1234,
            'mobile' => '1861403508'
        ]);

        $areas = Area::factory()->count(50)->create();
        $connectionPoints = ConnectionPoint::factory()->count(200)->create();
        $packages = Package::factory()->count(30)->create();

        $customers = Customer::factory()->count(200)->create()->each(function ($customer) use ($packages) {
            $packages = $packages->random()->take(mt_rand(1, 3))->get();
            $customer->packages()->attach($packages);
        });

        $employees = Employee::factory()->count(10)->create();
    }
}
