<?php

namespace Molitor\Address\Providers;

use Illuminate\Support\ServiceProvider;
use Molitor\Address\Repositories\AddressRepository;
use Molitor\Address\Repositories\AddressRepositoryInterface;
use Molitor\Address\Repositories\CityRepository;
use Molitor\Address\Repositories\CityRepositoryInterface;
use Molitor\Address\Repositories\CountryRepository;
use Molitor\Address\Repositories\CountryRepositoryInterface;

class AddressServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'address');
    }

    public function register()
    {
        $this->app->bind(AddressRepositoryInterface::class, AddressRepository::class);
        $this->app->bind(CountryRepositoryInterface::class, CountryRepository::class);
        $this->app->bind(CityRepositoryInterface::class, CityRepository::class);
    }
}
