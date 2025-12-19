<?php

namespace Molitor\Address\Repositories;

use Molitor\Address\Models\Address;

class AddressRepository implements AddressRepositoryInterface
{
    private Address $address;

    public function __construct()
    {
        $this->address = new Address();
    }

    public function createEmpty(): Address
    {
        return $this->address->create([]);
    }

    public function createEmptyId(): int
    {
        return $this->createEmpty()->id;
    }

    public function saveAddress(Address $address, array $values): void
    {
        $address->name = $values['name'] ?? '';
        $address->country_id = $values['country_id'] ?? null;
        $address->zip_code = $values['zip_code'] ?? '';
        $address->city = $values['city'] ?? '';
        $address->address = $values['address'] ?? '';
        $address->save();
    }

    public function delete(Address $address): bool
    {
        return $address->delete();
    }
}
