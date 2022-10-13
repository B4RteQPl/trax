<?php

namespace App\Rules;

use App\Models\Car;
use Illuminate\Contracts\Validation\Rule;

class AuthorizedUserIsCarOwner implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $car = Car::find($value);

        return $car->user->isAuthorized();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Car not belongs to authorized user!';
    }
}
