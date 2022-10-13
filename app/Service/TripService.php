<?php

namespace App\Service;

use App\Models\Car;
use InvalidArgumentException;

class TripService
{
    public static function calculateTotal(array $data)
    {
        if (!array_key_exists('car_id', $data)) {
            throw new InvalidArgumentException("car_id not provided!");
        }
        if (!array_key_exists('miles', $data)) {
            throw new InvalidArgumentException("miles not provided!");
        }

        $car = Car::find($data['car_id']);

        $carTotalMiles = $car->getTotalMiles();
        $tripMiles = $data['miles'];

        $carDistanceService = new CarDistanceService($carTotalMiles);
        return $carDistanceService->addToTotalDistance($tripMiles);
    }
}
