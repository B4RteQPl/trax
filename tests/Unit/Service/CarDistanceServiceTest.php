<?php

namespace Tests\Unit\Service;

use App\Service\CarDistanceService;
use Tests\TestCase;

class CarDistanceServiceTest extends TestCase
{

    /**
     * @test
     */
    public function when_distance_to_add_is_negative_then_exception_is_thrown()
    {
        // given
        $carDistanceService = new CarDistanceService(100);

        // expect assert
        $this->expectExceptionMessage("Adding new distance cannot decrease car distance balance!");
        $this->expectException(\OutOfRangeException::class);

        // when
        $newTotalCarDistance = $carDistanceService->addToTotalDistance(-200);
    }

    /**
     * @test
     */
    public function when_total_distance_is_negative_then_exception_is_thrown()
    {
        // expect assert
        $this->expectExceptionMessage("Millage Balance cannot be negative!");
        $this->expectException(\OutOfRangeException::class);

        // when
        $carDistanceService = new CarDistanceService(-100);
    }

    /**
     * @test
     * @dataProvider correctDistanceOperationsProvider
     */
    public function when_total_and_distance_to_add_is_float_then_new_total_distance_is_distance_is_calculated
    (
        float $totalDistance,
        float $distanceToAdd,
        float $calculationResult
    ) {
        // given
        $carDistanceService = new CarDistanceService($totalDistance);

        // when
        $newTotalCarDistance = $carDistanceService->addToTotalDistance($distanceToAdd);

        // then
        $this->assertEquals($newTotalCarDistance, $calculationResult);
    }

    /**
     * @return array[]
     */
    public function correctDistanceOperationsProvider(): array
    {
        return [
            '100 + 100 = 200' => [100, 100, 200],
            '100 + 100.5 = 200.5' => [100, 100.5, 200.5],
            '100.5 + 100 = 200.5' => [100.5, 100, 200.5],
            '100.5 + 100.5 = 201' => [100.5, 100.5, 201],
        ];
    }

}
