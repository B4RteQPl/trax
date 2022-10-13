<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Http\Requests\Car\CarStoreRequest;
use App\Http\Resources\Car\CarDetailResource;
use App\Http\Resources\Car\CarListResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class CarController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Car::class, 'car');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): ResourceCollection
    {
        $cars = auth()->user()->cars()->get();

        return CarListResource::collection($cars);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CarStoreRequest $request): CarListResource
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $car = Car::create($data);

        return new CarListResource($car);
    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car): CarDetailResource
    {
        return new CarDetailResource($car);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car): Response
    {
        $car->delete();

        return response()->noContent();
    }
}
