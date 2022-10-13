<?php

namespace App\Http\Requests\Trip;

use App\Rules\AuthorizedUserIsCarOwner;
use Illuminate\Foundation\Http\FormRequest;

class TripStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'date' => ['required', 'date'],
            'miles' => ['required', 'numeric', 'gt:0'],
            'car_id' => ['required', new AuthorizedUserIsCarOwner],
        ];
    }
}
