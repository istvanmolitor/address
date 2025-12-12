<?php

namespace Molitor\Address\Filament\Resources\CountryResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Molitor\Address\Filament\Resources\CountryResource;
use Molitor\Address\Repositories\CountryRepositoryInterface;

class EditCountry extends EditRecord
{
    protected static string $resource = CountryResource::class;

    public function getTitle(): string
    {
        return __('address::common.edit');
    }

    protected function afterSave(): void
    {
        if ($this->record?->is_default) {
            app(CountryRepositoryInterface::class)->setDefault($this->record);
        }
    }
}
