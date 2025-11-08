<?php

namespace Molitor\Address\Models;

use Molitor\Language\Models\TranslatableModel;

class Country extends TranslatableModel
{
    protected $fillable = [
        'code',
    ];

    public function getTranslationModelClass(): string
    {
        return CountryTranslation::class;
    }
}
