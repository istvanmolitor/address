<?php

namespace Molitor\Address\Repositories;

use Molitor\Address\Models\City;
use Molitor\Address\Models\Country;

interface CityRepositoryInterface
{
    public function getByZipCode(Country $country, string $zipCode): ?City;

    public function createCity(Country $country, string $zipCode, string $name): City;

    public function create(int $countryId, string $name, ?string $zipCode, bool $isValid): City;
}
