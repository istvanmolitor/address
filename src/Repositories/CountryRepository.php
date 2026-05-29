<?php

namespace Molitor\Address\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Molitor\Address\Models\Country;
use Molitor\Address\Models\CountryTranslation;
use Molitor\Language\Models\Language;

class CountryRepository implements CountryRepositoryInterface
{
    private Country $country;

    public function __construct()
    {
        $this->country = new Country;
    }

    public function getByCode(string $code): ?Country
    {
        return $this->country->where('code', $code)->first();
    }

    public function findOrCreate(string $code): Country
    {
        $country = $this->getByCode($code);
        if (! $country) {
            $country = new Country;
            $country->code = $code;
            $country->save();
        }

        return $country;
    }

    public function getAll(): Collection
    {
        return $this->country->get();
    }

    public function getOptions(): array
    {
        return $this->country->get()->pluck('name', 'id')->toArray();
    }

    public function getDefault(): ?Country
    {
        return $this->country->where('is_default', 1)->first();
    }

    public function getDefaultId(): ?int
    {
        return $this->getDefault()?->id;
    }

    public function setDefault(Country $country): void
    {
        // Set the given country as default and unset all others
        $country->is_default = true;
        $country->save();

        $this->country->where('id', '<>', $country->id)->update(['is_default' => false]);
    }

    public function getById(int $countryId): ?Country
    {
        return $this->country->where('id', $countryId)->first();
    }

    public function create(string $code, string $name, bool $isDefault): Country
    {
        $country = $this->country->create([
            'code' => $code,
            'is_default' => $isDefault,
        ]);

        $languageId = Language::query()
            ->where('code', (string) config('app.locale'))
            ->value('id');

        CountryTranslation::query()->updateOrCreate(
            ['country_id' => $country->id, 'language_id' => $languageId],
            ['name' => $name],
        );

        if ($isDefault) {
            $this->country->where('id', '<>', $country->id)->update(['is_default' => false]);
        }

        return $country;
    }
}
