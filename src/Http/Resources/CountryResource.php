<?php

namespace Molitor\Address\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Molitor\Language\Models\Language;

class CountryResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->resolveName(),
            'is_default' => (bool) $this->is_default,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }

    private function resolveName(): ?string
    {
        if (! $this->resource->relationLoaded('translations')) {
            return null;
        }

        $languageId = Language::query()
            ->where('code', (string) config('app.locale'))
            ->value('id');

        if ($languageId !== null) {
            $localized = $this->translations->firstWhere('language_id', $languageId);
            if ($localized !== null) {
                return $localized->name;
            }
        }

        return $this->translations->first()?->name;
    }
}
