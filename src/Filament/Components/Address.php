<?php

namespace Molitor\Address\Filament\Components;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Molitor\Address\Repositories\CountryRepositoryInterface;

class Address
{
    public static function make(string $name, string $label) {
        return Section::make($label)->schema([
            TextInput::make('name')->label('Számlázási név')->maxLength(255),
            Select::make('country_id')->label('Ország')
                ->options(app(CountryRepositoryInterface::class)->getAll()->mapWithKeys(fn($c) => [$c->id => $c->name])->toArray())
                ->default(app(CountryRepositoryInterface::class)->getDefaultId())->searchable(),
            TextInput::make('zip_code')->label('Irányítószám')->maxLength(10),
            TextInput::make('city')->label('Város')->maxLength(255),
            TextInput::make('address')->label('Cím')->maxLength(255),
        ])->statePath($name)->columns(2)->columnSpanFull();
    }
}
