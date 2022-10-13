<?php

namespace Tests\Feature\Service;

use App\Models\Car;
use App\Service\TripService;
use Tests\TestCase;

class TripServiceTest extends TestCase
{

    /**
     * @test
     */
    public function when_miles_and_car_id_exists_then_trip_service_will_calculate_total_car_distance()
    {
        // given
        $car = Car::factory()->create();
        $data = [
            'miles' => 100,
            'car_id' => $car->id,
        ];

        $newCarDistance = TripService::calculateTotal($data);

        $this->assertEquals($newCarDistance, 100);
    }

    /**
     * @test
     */
    public function when_miles_is_not_provided_then_error_is_thrown()
    {
        $this->expectExceptionMessage("miles not provided!");
        $this->expectException(\InvalidArgumentException::class);

        // given
        $car = Car::factory()->create();
        $data = ['car_id' => $car->id];

        $newCarDistance = TripService::calculateTotal($data);
    }

    /**
     * @test
     */
    public function when_car_id_is_not_provided_then_error_is_thrown()
    {
        $this->expectExceptionMessage("car_id not provided!");
        $this->expectException(\InvalidArgumentException::class);

        // given
        $car = Car::factory()->create();
        $data = ['miles' => 2000];

        $newCarDistance = TripService::calculateTotal($data);
    }

}
