<?php

namespace Molitor\Address\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Molitor\Address\Models\Country;

interface CountryRepositoryInterface
{
    public function getAll(): Collection;

    public function getOptions(): array;

    public function getByCode(string $code): ?Country;

    public function findOrCreate(string $code): Country;

    public function getDefaultId(): ?int;

    public function setDefault(Country $country): void;

    public function getById(int $countryId): ?Country;

    public function create(string $code, string $name, bool $isDefault): Country;
}
