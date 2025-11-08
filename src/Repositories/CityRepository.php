<?php

namespace Molitor\Address\Repositories;

use Molitor\Address\Models\City;
use Molitor\Address\Models\Country;

class CityRepository implements CityRepositoryInterface
{
    protected City $city;

    public function __construct()
    {
        $this->city = new City();
    }

    public function getByZipCode(Country $country, string $zipCode): ?City
    {
        return $this->city->where('country_id', $country->id)->where('zip_code', $zipCode)->first();
    }

    public function createCity(Country $country, string $zipCode, string $name): City
    {
        $city = $this->getByZipCode($country, $zipCode);
        if (!$city) {
            $city = new City();
            $city->country_id = $country->id;
            $city->zip_code = $zipCode;
            $city->name = $name;
            $city->save();
        }
        return $city;
    }
}