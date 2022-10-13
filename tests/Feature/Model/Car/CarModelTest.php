<?php

namespace Tests\Feature\Model\Car;

use App\Models\Car;
use App\Models\Trip;
use Tests\TestCase;

class CarModelTest extends TestCase
{

    /**
     * @test
     */
    public function when_trip_is_added_to_car_then_total_count_returns_int_value_of_counted_trips()
    {
        // given
        $dynamicAmount = random_int(0, 3);
        $car = Car::factory()->create();

        // when
        Trip::factory($dynamicAmount)->create(['car_id' => $car->id]);

        // then
        $this->assertEquals($car->getTotalCount(), $dynamicAmount);

        // when add one more to test counter
        Trip::factory(1)->create(['car_id' => $car->id]);

        // then
        $this->assertEquals($car->getTotalCount(), $dynamicAmount + 1);
    }

    /**
     * @test
     */
    public function when_trips_is_added_to_car_then_total_miles_calculetes_car_total_based_on_trip_miles()
    {
        // given
        $car = Car::factory()->create();

        // when
        Trip::factory()->create(['car_id' => $car->id, 'miles' => 100.55]);

        // then
        $this->assertEquals($car->getTotalMiles(), 100.55);

        // when after
        Trip::factory()->create(['car_id' => $car->id, 'miles' => 100.55]);

        // then
        $this->assertEquals($car->getTotalMiles(), 201.1);
    }

}
