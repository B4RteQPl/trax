<?php

namespace App\Http\Controllers;

use App\Http\Requests\Trip\TripStoreRequest;
use App\Http\Resources\Trip\TripListResource;
use App\Models\Trip;
use App\Service\TripService;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TripController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Trip::class, 'trip');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): ResourceCollection
    {
        $trips = auth()->user()->trips()->with('car')->get();

        return TripListResource::collection($trips);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(TripStoreRequest $request): TripListResource
    {
        $data = $request->validated();

        $data['total'] = TripService::calculateTotal($data);

        $trip = Trip::create($data);

        return new TripListResource($trip);
    }
}
