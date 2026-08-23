# Address modul

Egyszerű címkezelő csomag Laravel projektekhez: országok, városok és cím rekordok, REST API kontrollerekkel és seederekkel.

## Előfeltételek

- Laravel alkalmazás
- `istvanmolitor/user` (composer `require`)
- `istvanmolitor/language` – nincs a composer.json `require`-jában, de a `CountryApiController` a `Molitor\Language\Models\Language` modellt használja az országnevek fordításához, ezért gyakorlatban ez is szükséges

## Telepítés

A csomag a composer autoload és a Laravel package auto-discovery segítségével automatikusan regisztrálja a szolgáltatóját:

- Molitor\Address\Providers\AddressServiceProvider

Külön teendő általában nincs, csak telepítés után futtasd a migrációkat.

## Migrációk

A csomag automatikusan betölti a saját migrációit (országok, városok, címek és fordítások táblák). A táblák létrehozásához futtasd a szokásos parancsot:

```bash
php artisan migrate
```

## Seeder (opcionális, de ajánlott)

A csomag biztosít egy AddressSeeder osztályt, amely feltölti a magyar ország- és városadatokat, valamint létrehoz egy szükséges jogosultságot ("country").

Registrálás a database/seeders/DatabaseSeeder.php fájlban:
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

Futtatás:
```bash
php artisan db:seed --class=Molitor\\Address\\database\\seeders\\AddressSeeder
```

Megjegyzés: A seeder a Molitor User/ACL szolgáltatásait használja a jogosultság létrehozásához, és a Language modul fordítási funkcióira támaszkodik az ország nevekhez.

## Admin API

A csomag nem Filament-et használ, hanem sima REST API kontrollereket ad az admin felülethez:

- `Molitor\Address\Http\Controllers\Api\CountryApiController` – országok CRUD-ja, fordítással
- `Molitor\Address\Http\Controllers\Api\CityApiController` – városok CRUD-ja

Route-ok (`src/routes/api.php`), `auth:sanctum` és `permission:country` middleware-rel védve:

```php
Route::prefix('admin/address')
    ->middleware(['api', 'auth:sanctum', 'permission:country'])
    ->name('address.')
    ->group(function (): void {
        Route::resource('countries', CountryApiController::class);
        Route::resource('cities', CityApiController::class);
    });
```

Jogosultság ellenőrzés máshol (pl. menüben):
```php
Gate::allows('acl', 'country')
```

## Repozitóriumok és használati példák

A csomag szolgáltatásait az IoC konténeren keresztül éred el. A ServiceProvider a következő interfészeket köti a megvalósításokhoz:
- Molitor\Address\Repositories\AddressRepositoryInterface → AddressRepository
- Molitor\Address\Repositories\CountryRepositoryInterface → CountryRepository
- Molitor\Address\Repositories\CityRepositoryInterface → CityRepository

Példák:

```php
use Molitor\Address\Models\Address;
use Molitor\Address\Repositories\AddressRepositoryInterface;
use Molitor\Address\Repositories\CountryRepositoryInterface;
use Molitor\Address\Repositories\CityRepositoryInterface;

// Üres cím létrehozása és mentése
/** @var AddressRepositoryInterface $addresses */
$addresses = app(AddressRepositoryInterface::class);
$address = $addresses->createEmpty();
$addresses->saveAddress($address, [
    'name' => 'Teszt Kft.',
    'country_id' => app(CountryRepositoryInterface::class)->getDefaultId(), // pl. HU
    'zip_code' => '1011',
    'city' => 'Budapest',
    'address' => 'Fő utca 1.',
]);

// Ország lekérdezése, létrehozása kóddal
/** @var CountryRepositoryInterface $countries */
$countries = app(CountryRepositoryInterface::class);
$hu = $countries->findOrCreate('hu');
$all = $countries->getAll();
$defaultId = $countries->getDefaultId();

// Város létrehozása irányítószám alapján
/** @var CityRepositoryInterface $cities */
$cities = app(CityRepositoryInterface::class);
$city = $cities->createCity($hu, '1011', 'Budapest');
```

## Fordítások

A csomag fordításokat tölt be az „address” névtérrel (resources/lang). Amennyiben saját szövegeket szeretnél használni, projekt szinten felüldefiniálhatod az azonos kulcsokat.

## Licenc

Belső modul. Használat a projekt irányelvei szerint.
