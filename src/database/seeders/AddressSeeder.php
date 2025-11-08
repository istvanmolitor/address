<?php

namespace Molitor\Address\database\seeders;

use Illuminate\Database\Seeder;
use Molitor\Address\Repositories\CityRepositoryInterface;
use Molitor\Address\Repositories\CountryRepository;
use Molitor\Address\Repositories\CountryRepositoryInterface;
use Molitor\User\Exceptions\PermissionException;
use Molitor\User\Services\AclManagementService;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            /** @var AclManagementService $aclService */
            $aclService = app(AclManagementService::class);
            $aclService->createPermission('country', 'Országok szerkesztése', 'admin');
        } catch (PermissionException $e) {
            $this->command->error($e->getMessage());
        }

        $countryData = require(__DIR__ . '/data/hu_countries.php');
        /** @var CountryRepositoryInterface $countryRepository */
        $countryRepository = app(CountryRepositoryInterface::class);

        foreach ($countryData as $code => $name) {
            $country = $countryRepository->findOrCreate($code);
            $country->setCurrentCode('hu');
            $country->name = $name;
            $country->save();
        }

        $country = $countryRepository->findOrCreate('hu');

        $citiesData = require(__DIR__ . '/data/hu_cities.php');
        /** @var CityRepositoryInterface $cityRepository */
        $cityRepository = app(CityRepositoryInterface::class);

        foreach ($citiesData as $zipCode => $name) {
            $cityRepository->createCity($country, (string)$zipCode, $name);
        }
    }
}
