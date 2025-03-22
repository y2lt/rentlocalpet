<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('pet owner');
    }

    public function rules(): array
    {
        return [
            'pet_type_id' => ['required', 'exists:pet_types,id'],
            'breed_id' => ['required', 'exists:breeds,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'age' => ['required', 'integer', 'min:0'],
            'daily_rate' => ['required', 'numeric', 'min:0'],
            'photos' => ['required', 'array', 'min:1'],
            'photos.*' => ['required', 'string', 'url']
        ];
    }
}
