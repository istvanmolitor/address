<?php

namespace Molitor\Address\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Molitor\Language\Models\Language;

class CityResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'is_valid' => (bool) $this->is_valid,
            'country_id' => $this->country_id,
            'country' => $this->when($this->relationLoaded('country'), [
                'id' => $this->country?->id,
                'code' => $this->country?->code,
                'name' => $this->resolveCountryName(),
            ]),
            'name' => $this->name,
            'zip_code' => $this->zip_code,
        ];
    }

    private function resolveCountryName(): ?string
    {
        if ($this->country === null || ! $this->country->relationLoaded('translations')) {
            return null;
        }

        static $languageId = null;

        if ($languageId === null) {
            $languageId = Language::query()
                ->where('code', (string) config('app.locale'))
                ->value('id');
        }

        if ($languageId !== null) {
            $localized = $this->country->translations->firstWhere('language_id', $languageId);
            if ($localized !== null) {
                return $localized->name;
            }
        }

        return $this->country->translations->first()?->name;
    }
}
