<?php

namespace Molitor\Address\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'country_id',
        'zip_code',
        'name',
    ];

    public $timestamps = false;
}