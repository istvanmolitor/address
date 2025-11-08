<?php

namespace Molitor\Address\Repositories;

use Molitor\Address\Models\Country;
use Illuminate\Database\Eloquent\Collection;

interface CountryRepositoryInterface
{
    public function getAll(): Collection;

    public function getByCode(string $code): Country|null;

    public function findOrCreate(string $code): Country;

    public function getDefaultId(): int|null;
}
