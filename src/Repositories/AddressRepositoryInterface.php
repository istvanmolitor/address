<?php

namespace Molitor\Address\Repositories;

use Molitor\Address\Models\Address;

interface AddressRepositoryInterface
{
    public function createEmpty(): Address;

    public function createEmptyId(): int;

    public function saveAddress(Address $address, array $values): void;
}
