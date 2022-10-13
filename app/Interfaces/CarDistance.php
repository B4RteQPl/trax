<?php

namespace App\Interfaces;

interface CarDistance
{
    public function __construct(float $totalDistance);
    public function addToTotalDistance(float $distanceToAdd): float;
}
