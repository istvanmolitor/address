<?php

namespace Molitor\Address\Models;

use Molitor\Language\Models\TranslatableModel;

class Country extends TranslatableModel
{
    protected $fillable = [
        'code',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'bool',
    ];

    public function getTranslationModelClass(): string
    {
        return CountryTranslation::class;
    }
}
