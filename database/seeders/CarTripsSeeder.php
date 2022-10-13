<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Trip;
use Illuminate\Database\Seeder;

class CarTripsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Car::factory(20)->create()->each(function($car) {
            Trip::factory(rand(1,10))
                ->create(['car_id' => $car->id]);
        });
    }
}
