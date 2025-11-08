<?php

namespace Molitor\Address\Filament\Resources\CountryResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Molitor\Address\Filament\Resources\CountryResource;

class CreateCountry extends CreateRecord
{
    protected static string $resource = CountryResource::class;

    public function getTitle(): string
    {
        return __('address::common.create');
    }
}
