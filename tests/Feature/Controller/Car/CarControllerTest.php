<?php

namespace Tests\Feature\Controller\Car;

use App\Models\Car;
use App\Models\User;
use Tests\TestCase;

class CarControllerTest extends TestCase
{

    // store car

    /**
     * @test
     */
    public function when_user_is_authorized_then_can_add_user_car()
    {
        // given
        $user = User::factory()->create();
        $data = [
            'make' => 'Aston',
            'model' => 'Martin',
            'year' => 2022,
        ];

        // when
        $this->actingAs($user, 'api');
        $response = $this->postJson(route('cars.store'), $data);

        // then
        $response->assertCreated();
        $response->assertJsonStructure(['data' => ['id', 'year', 'make', 'model']]);

        $carId = $response->json('data.id');
        $addedCar = Car::find($carId);

        $this->assertEquals($user->id, $addedCar->user_id);
        $this->assertEquals($data['make'], $addedCar->make);
        $this->assertEquals($data['model'], $addedCar->model);
        $this->assertEquals($data['year'], $addedCar->year);
    }

    /**
     * @test
     */
    public function when_user_is_unauthorized_then_can_not_add_user_car()
    {
        // given
        $data = [
            'make' => 'Not existing make',
            'model' => 'Not existing model',
            'year' => 2022,
        ];

        // when
        $response = $this->postJson(route('cars.store'), $data);

        // then
        $response->assertUnauthorized();
        $this->assertDatabaseMissing('cars', [
            'make' => $data['make'],
            'model' => $data['model']
        ]);
    }

    // store car validation

    /**
     * @test
     * @dataProvider carRequiredFieldsProvider
     */
    public function when_required_fields_are_not_provided_then_validation_fails(string $requiredField)
    {
        // given
        $user = User::factory()->create();
        $data = [
            'make' => 'Aston',
            'model' => 'Martin',
            'year' => 2022,
        ];
        unset($data[$requiredField]);

        // when
        $this->actingAs($user, 'api');
        $response = $this->postJson(route('cars.store'), $data);

        // then
        $response->assertUnprocessable();
        $response->assertJsonValidationErrors([$requiredField]);
    }

    /**
     * @test
     * @dataProvider carStringFieldsProvider
     */
    public function when_string_fields_are_too_long_then_validation_fails(string $stringField)
    {
        // given
        $user = User::factory()->create();
        $data = [
            'make' => 'Aston',
            'model' => 'Martin',
            'year' => 2022,
        ];
        $data[$stringField] = str_random(256);

        // when
        $this->actingAs($user, 'api');
        $response = $this->postJson(route('cars.store'), $data);

        // then
        $response->assertUnprocessable();
        $response->assertJsonValidationErrors([$stringField]);
    }

    /**
     * @test
     * @dataProvider CarInvalidYearsProvider
     */
    public function when_car_year_is_not_4_digits_then_validation_fails(int $year)
    {
        // given
        $user = User::factory()->create();
        $data = [
            'make' => 'Aston',
            'model' => 'Martin',
            'year' => $year
        ];

        // when
        $this->actingAs($user, 'api');
        $response = $this->postJson(route('cars.store'), $data);

        // then
        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['year']);
    }

    // delete car

    /**
     * @test
     */
    public function when_user_is_authorized_then_can_delete_user_car()
    {
        // given
        $user = User::factory()->create();
        $carToDelete = Car::factory()->create(['user_id' => $user->id]);

        // when
        $this->actingAs($user, 'api');
        $response = $this->deleteJson(route('cars.destroy', ['car' => $carToDelete->id]));

        // then
        $response->assertNoContent();
        $this->assertDatabaseMissing('cars', ['id' => $carToDelete->id]);
    }

    /**
     * @test
     */
    public function when_user_is_authorized_then_can_not_delete_car_of_another_user()
    {
        // given
        $user = User::factory()->create();
        $carToDelete = Car::factory()->create(['user_id' => $user->id]);

        $anotherUser = User::factory()->create();

        // when
        $this->actingAs($anotherUser, 'api');
        $response = $this->deleteJson(route('cars.destroy', ['car' => $carToDelete->id]));

        // then
        $response->assertForbidden();
        $this->assertDatabaseHas('cars', ['id' => $carToDelete->id]);
    }

    /**
     * @test
     */
    public function when_user_is_unauthorized_then_can_not_delete_user_car()
    {
        // given
        $user = User::factory()->create();
        $carToDelete = Car::factory()->create(['user_id' => $user->id]);

        // when
        $response = $this->deleteJson(route('cars.destroy', ['car' => $carToDelete->id]));

        // then
        $response->assertUnauthorized();
        $this->assertDatabaseHas('cars', ['id' => $carToDelete->id]);
    }



    // get car

    /**
     * @test
     */
    public function when_user_is_authorized_then_can_get_own_car()
    {
        // given
        $user = User::factory()->create();
        $carToShow = Car::factory()->create(['user_id' => $user->id]);
        $carToNotShow = Car::factory()->create(['user_id' => $user->id]);

        // when
        $this->actingAs($user, 'api');
        $response = $this->getJson(route('cars.show', ['car' => $carToShow->id]));

        // then
        $response->assertOk();
        $response->assertJsonStructure(['data' => ['id', 'year', 'make', 'model', 'trip_count', 'trip_miles']]);
        $response->assertJsonFragment(['id' => $carToShow->id]);
    }

    /**
     * @test
     */
    public function when_user_is_authorized_then_can_not_get_car_of_another_user()
    {
        // given
        $user = User::factory()->create();
        $userCar = Car::factory()->create(['user_id' => $user->id]);

        $anotherUser = User::factory()->create();

        // when
        $this->actingAs($anotherUser, 'api');
        $response = $this->getJson(route('cars.show', ['car' => $userCar->id]));

        // then
        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function when_user_is_authorized_then_can_get_only_own_cars()
    {
        // given
        $user = User::factory()->create();
        $userCars = Car::factory(2)->create(['user_id' => $user->id]);

        $anotherUser = User::factory()->create();
        $anotherUserCar = Car::factory(1)->create(['user_id' => $anotherUser->id]);

        // when
        $this->actingAs($user, 'api');
        $response = $this->getJson(route('cars.index'));

        // then
        $response->assertOk();
        $this->assertCount(2, $response->json('data'));

        foreach ($userCars as $car) {
            $response->assertJsonFragment([
                'id' => $car->id,
            ]);
        }
    }

    // get cars

    /**
     * @test
     */
    public function when_user_is_unauthorized_then_can_not_get_list_of_user_cars()
    {

        // given
        $user = User::factory()->create();
        $userCar = Car::factory()->create(['user_id' => $user->id]);

        // when
        $response = $this->getJson(route('cars.index'));

        // then
        $response->assertUnauthorized();
    }

    /**
     * @return array[]
     */
    public function carStringFieldsProvider(): array
    {
        return [
            'make' => ['make'],
            'model' => ['model'],
        ];
    }

    /**
     * @return array[]
     */
    public function carRequiredFieldsProvider(): array
    {
        return [
            'make' => ['make'],
            'model' => ['model'],
            'year' => ['year'],
        ];
    }

    /**
     * @return array[]
     */
    public function carInvalidYearsProvider(): array
    {
        return [
            'too long' => [12211],
            'too short' => [999],
        ];
    }
}
