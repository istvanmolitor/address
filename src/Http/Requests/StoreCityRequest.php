<?php

namespace Molitor\Address\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'country_id' => ['required', 'integer', 'exists:countries,id'],
            'name' => ['required', 'string', 'max:255'],
            'zip_code' => ['nullable', 'string', 'max:10'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'country_id.required' => 'Az ország mező kitöltése kötelező.',
            'country_id.exists' => 'A kiválasztott ország nem létezik.',
            'name.required' => 'A város név mező kitöltése kötelező.',
        ];
    }
}
