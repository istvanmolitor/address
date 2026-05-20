<?php

namespace Molitor\Address\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Molitor\Address\Models\Country;

class UpdateCountryRequest extends FormRequest
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
        /** @var Country $country */
        $country = $this->route('country');

        return [
            'code' => ['required', 'string', 'max:5', Rule::unique('countries', 'code')->ignore($country->id)],
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

