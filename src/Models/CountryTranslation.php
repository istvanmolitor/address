<?php

namespace Molitor\Address\Models;

use Illuminate\Database\Eloquent\Model;
use Molitor\Language\Models\TranslationModel;

class CountryTranslation extends TranslationModel
{
    public $timestamps = false;

    public function getTranslatableModelClass(): string
    {
        return TranslationModel::class;
    }

    public function getTranslationForeignKey(): string
    {
        return 'country_id';
    }

    public function getTranslatableFields(): array
    {
        return [
            'name',
        ];
    }
}
