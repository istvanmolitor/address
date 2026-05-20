<?php

namespace Molitor\Address\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    protected $fillable = [
        'is_valid',
        'country_id',
        'zip_code',
        'name',
    ];

    protected $casts = [
        'is_valid' => 'bool',
    ];

    public $timestamps = false;

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
