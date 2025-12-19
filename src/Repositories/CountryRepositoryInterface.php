<?php

namespace Molitor\Address\Repositories;

use Molitor\Address\Models\Country;
use Illuminate\Database\Eloquent\Collection;

interface CountryRepositoryInterface
{
    public function getAll(): Collection;

    public function getOptions(): array;

    public function getByCode(string $code): Country|null;

    public function findOrCreate(string $code): Country;

    public function getDefaultId(): int|null;

    public function setDefault(Country $country): void;

    public function getById(int $countryId): Country|null;
}
