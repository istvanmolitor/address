<?php

namespace Molitor\Address\Tests\Feature;

use Molitor\Address\Providers\AddressServiceProvider;
use Tests\TestCase;

class PackageSmokeTest extends TestCase
{
    public function test_service_provider_is_loaded(): void
    {
        $this->assertTrue(class_exists(AddressServiceProvider::class));
        $this->assertTrue($this->app->providerIsLoaded(AddressServiceProvider::class));
    }
}

