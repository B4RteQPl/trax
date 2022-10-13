<?php

namespace App\Service;

use App\Interfaces\CarDistance;
use OutOfRangeException;

final class CarDistanceService implements CarDistance
{
    private float $totalDistance;

    public function __construct(float $totalDistance)
    {
        if ($totalDistance < 0) {
            throw new OutOfRangeException("Millage Balance cannot be negative!");
        }

        $this->totalDistance = $totalDistance;
    }


    public function addToTotalDistance(float $distanceToAdd): float
    {
        if ($distanceToAdd < 0) {
            throw new OutOfRangeException("Adding new distance cannot decrease car distance balance!");
        }

        return $this->totalDistance += $distanceToAdd;
    }
}
