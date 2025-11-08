# Adress modul

Címek kezelése

## Előfeltételek

Telepíteni kell a következő modulokat.:
- https://gitlab.com/molitor/admin

## Telepítés

### Provider regisztrálása
config/app.php
```php
'providers' => ServiceProvider::defaultProviders()->merge([
    /*
    * Package Service Providers...
    */
    \Molitor\Address\Providers\AddressServiceProvider::class,
])->toArray(),
```

### Seeder regisztrálása

database/seeders/DatabaseSeeder.php
```php

use Molitor\Address\database\seeders\AddressSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AddressSeeder::class,
        ]);
    }
}
```