<?php

namespace App\Http\Resources\Trip;

use App\Http\Resources\Car\CarDetailResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TripListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'miles' => $this->miles,
            'date' => $this->date,
            'total' => $this->total,
            'car' => new CarDetailResource($this->car)
        ];
    }
}
