<?php

namespace Tests\Feature\Model\User;

use App\Models\Car;
use App\Models\User;
use Tests\TestCase;

class UserModelTest extends TestCase
{

    /**
     * @test
     */
    public function when_user_is_authorized_then_isAuthorized_returns_true()
    {
        // given
        $user = User::factory()->create();

        // when
        $this->actingAs($user, 'api');

        // then
        $this->assertTrue($user->isAuthorized());
    }

    /**
     * @test
     */
    public function when_user_is_unauthorized_then_isAuthorized_returns_false()
    {
        // given
        $user = User::factory()->create();

        // when
        // $this->actingAs($user, 'api');

        // then
        $this->assertFalse($user->isAuthorized());
    }

    /**
     * @test
     */
    public function when_user_is_car_owner_then_isCarOwner_returns_true()
    {
        // given
        $user = User::factory()->create();

        // when
        $userCar = Car::factory()->create(['user_id' => $user->id]);

        // then
        $this->assertTrue($user->isCarOwner($userCar));
    }

    /**
     * @test
     */
    public function when_user_is_not_car_owner_then_isCarOwner_returns_false()
    {
        // given
        $user = User::factory()->create();

        // when
        $anotherCar = Car::factory()->create();

        // then
        $this->assertFalse($user->isCarOwner($anotherCar));
    }


}
