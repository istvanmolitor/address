<?php

namespace Molitor\Address\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Molitor\Address\Models\Country;

class CountryRepository implements CountryRepositoryInterface
{
    private Country $country;

    public function __construct()
    {
        $this->country = new Country();
    }

    public function getByCode(string $code): Country|null
    {
        return $this->country->where('code', $code)->first();
    }

    public function findOrCreate(string $code): Country
    {
        $country = $this->getByCode($code);
        if (!$country) {
            $country = new Country();
            $country->code = $code;
            $country->save();
        }
        return $country;
    }

    public function getAll(): Collection
    {
        return $this->country->get();
    }

    public function getDefaultId(): int|null
    {
        return $this->country->where('is_default', 1)->first()?->id;
    }

    public function setDefault(Country $country): void
    {
        // Set the given country as default and unset all others
        $country->is_default = true;
        $country->save();

        $this->country->where('id', '<>', $country->id)->update(['is_default' => false]);
    }
}
