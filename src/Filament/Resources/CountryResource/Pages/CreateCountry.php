<?php

namespace Molitor\Address\Filament\Resources\CountryResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Molitor\Address\Filament\Resources\CountryResource;
use Molitor\Address\Repositories\CountryRepositoryInterface;

class CreateCountry extends CreateRecord
{
    protected static string $resource = CountryResource::class;

    public function getTitle(): string
    {
        return __('address::common.create');
    }

    protected function afterCreate(): void
    {
        if ($this->record?->is_default) {
            app(CountryRepositoryInterface::class)->setDefault($this->record);
        }
    }
}
