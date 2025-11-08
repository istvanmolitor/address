<?php

namespace Molitor\Address\Filament\Resources\CountryResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Molitor\Address\Filament\Resources\CountryResource;

class EditCountry extends EditRecord
{
    protected static string $resource = CountryResource::class;

    public function getTitle(): string
    {
        return __('address::common.edit');
    }
}
