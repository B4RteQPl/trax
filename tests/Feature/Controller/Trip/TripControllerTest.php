<?php

namespace Tests\Feature\Controller\Trip;

use App\Models\Car;
use App\Models\Trip;
use App\Models\User;
use Tests\TestCase;

class TripControllerTest extends TestCase
{

    // store trip

    /**
     * @test
     */
    public function when_user_is_authorized_then_can_add_user_trip_with_car()
    {
        // given
        $user = User::factory()->create();
        $userCar = Car::factory()->create(['user_id' => $user->id]);

        $data = [
            'date' => '2022-01-01',
            'miles' => 100,
            'car_id' => $userCar->id
        ];

        // when
        $this->actingAs($user, 'api');
        $response = $this->postJson(route('trips.store'), $data);

        // then
        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'date',
                'miles',
                'total',
                'car' => ['id', 'year', 'make', 'model', 'trip_count', 'trip_miles']
            ]
        ]);

        $newTrip = $user->trips()->first();
        $newTripIdFromResponse = $response->json('data.id');
        $this->assertEquals($newTrip->id, $newTripIdFromResponse);

        $this->assertEquals($userCar->id, $newTrip->car_id);
        $this->assertEquals($data['date'], $newTrip->date->toDateString());
        $this->assertEquals($data['miles'], $newTrip->miles);
    }

    /**
     * @test
     */
    public function when_user_is_unauthorized_then_can_not_add_user_trip()
    {
        // given
        $user = User::factory()->create();
        $userCar = Car::factory()->create(['user_id' => $user->id]);

        $data = [
            'date' => '2022-01-01',
            'miles' => 100,
            'car_id' => $userCar->id
        ];

        // when
        $response = $this->postJson(route('trips.store'), $data);

        // then
        $response->assertUnauthorized();
        $this->assertEquals('0', $userCar->trips->count());
    }

    // store trip validation

    /**
     * @test
     */
    public function when_user_is_authorized_but_car_belongs_to_different_user_then_validation_fails()
    {
        // given
        $user = User::factory()->create();
        $userCar = Car::factory()->create(['user_id' => $user->id]);

        $anotherUser = User::factory()->create();

        $data = [
            'date' => '2022-01-01',
            'miles' => 100,
            'car_id' => $userCar->id
        ];

        // when
        $this->actingAs($anotherUser, 'api');
        $response = $this->postJson(route('trips.store'), $data);

        // then
        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['car_id']);
    }

    /**
     * @test
     * @dataProvider tripRequiredFieldsProvider
     */
    public function when_required_fields_are_not_provided_then_validation_fails(string $requiredField)
    {
        // given
        $user = User::factory()->create();
        $userCar = Car::factory()->create(['user_id' => $user->id]);

        $data = [
            'date' => '2022-01-01',
            'miles' => 100,
            'car_id' => $userCar->id
        ];

        unset($data[$requiredField]);

        // when
        $this->actingAs($user, 'api');
        $response = $this->postJson(route('trips.store'), $data);

        // then
        $response->assertUnprocessable();
        $response->assertJsonValidationErrors([$requiredField]);
    }

    /**
     * @test
     * @dataProvider milesInvalidDataProvider
     */
    public function when_miles_are_invalid_then_validation_fails($miles)
    {
        // given
        $user = User::factory()->create();
        $userCar = Car::factory()->create(['user_id' => $user->id]);

        $data = [
            'date' => '2022-01-01',
            'miles' => $miles,
            'car_id' => $userCar->id
        ];

        // when
        $this->actingAs($user, 'api');
        $response = $this->postJson(route('trips.store'), $data);

        // then
        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['miles']);
    }

    // get trips

    /**
     * @test
     */
    public function when_user_is_authorized_then_can_get_list_of_user_trips()
    {
        // given
        // 4 trips using 2 cars
        $user = User::factory()->create();
        $userCars = Car::factory(2)->create(['user_id' => $user->id]);
        foreach ($userCars as $car) {
            Trip::factory(2)->create(['car_id' => $car->id]);
        }

        // another 2 trips using 1 car
        $anotherUser = User::factory()->create();
        $anotherUserCar = Car::factory()->create(['user_id' => $anotherUser->id]);
        $anotherUserTrips = Trip::factory(2)->create(['car_id' => $anotherUserCar->id]);

        // when
        $this->actingAs($user, 'api');
        $response = $this->getJson(route('trips.index'));

        // then
        $response->assertOk();
        $this->assertCount(4, $response->json('data'));

        $userTrips = $user->trips()->get();
        foreach ($userTrips as $trip) {
            $response->assertJsonFragment([
                'id' => $trip->id,
            ]);
        }
    }

    /**
     * @test
     */
    public function when_user_is_unauthorized_then_can_not_get_list_of_user_trips()
    {
        // given
        $user = User::factory()->create();
        $userCar = Car::factory()->create(['user_id' => $user->id]);
        $userTrips = Trip::factory(2)->create(['car_id' => $userCar->id]);

        // when
        $response = $this->getJson(route('trips.index'));

        // then
        $response->assertUnauthorized();
    }

    /**
     * @return array[]
     */
    public function tripRequiredFieldsProvider(): array
    {
        return [
            'date' => ['date'],
            'miles' => ['miles'],
            'car_id' => ['car_id'],
        ];
    }

    /**
     * @return array[]
     */
    public function milesInvalidDataProvider(): array
    {
        return [
            'when smaller then 0' => [-100],
            'when equal 0' => [0],
            'when not numeric' => ['a100'],
        ];
    }

}
