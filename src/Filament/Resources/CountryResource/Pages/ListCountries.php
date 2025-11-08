<?php

namespace Molitor\Address\Filament\Resources\CountryResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Molitor\Address\Filament\Resources\CountryResource;

class ListCountries extends ListRecords
{
    protected static string $resource = CountryResource::class;

    public function getBreadcrumb(): string
    {
        return __('address::common.list');
    }

    public function getTitle(): string
    {
        return __('address::country.title');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('address::country.create'))
                ->icon('heroicon-o-plus'),
        ];
    }
}
