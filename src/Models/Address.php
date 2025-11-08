<?php

namespace Molitor\Address\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'name',
        'country_id',
        'zip_code',
        'city',
        'address',
    ];

    public function country() {
        return $this->belongsTo(Country::class);
    }
}
