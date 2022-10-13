<?php

namespace App\Http\Requests\Car;

use Illuminate\Foundation\Http\FormRequest;

class CarStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'year' => ['required', 'digits:4'],
            'make' => ['required', 'max:255'],
            'model' => ['required', 'max:255']
        ];
    }
}
