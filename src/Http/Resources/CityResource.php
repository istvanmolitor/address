<?php

namespace Molitor\Address\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'country_id' => $this->country_id,
            'country' => $this->when($this->relationLoaded('country'), [
                'id' => $this->country?->id,
                'code' => $this->country?->code,
                'name' => $this->country?->name,
            ]),
            'name' => $this->name,
            'zip_code' => $this->zip_code,
        ];
    }
}
