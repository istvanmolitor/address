<?php

namespace Molitor\Address\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCountryRequest extends FormRequest
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
            'code' => ['required', 'string', 'max:5', 'unique:countries,code'],
            'name' => ['required', 'string', 'max:255'],
            'is_default' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.required' => 'A kód mező kitöltése kötelező.',
            'code.unique' => 'Ez a kód már létezik.',
            'name.required' => 'A név mező kitöltése kötelező.',
        ];
    }
}

