<?php

namespace Bitrio\CityDelivery\Models;

use Illuminate\Database\Eloquent\Model;
use Bitrio\CityDelivery\Models\CountryState;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Core\Models\Country;

class CityDelivery extends Model
{
    use SoftDeletes;
    protected $table = 'city_deliveries';
    protected $fillable = [
        'country_state_id',
        'name',
        'municipal_code',
        'delivery_cost',
        'is_active',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Relación con el estado (CountryState)
     */
    public function state()
    {
        return $this->belongsTo(CountryState::class, 'country_state_id');
    }


    /**
     * Relación con el país (a través del estado)
     */
    public function country()
    {
        return $this->hasOneThrough(
            Country::class,
            CountryState::class,
            'id',           // Foreign key en country_states
            'id',           // Foreign key en countries
            'country_state_id', // Foreign key en city_deliveries
            'country_id'    // Foreign key en country_states
        );
    }

    /**
     * Accesor para simplificar el acceso al nombre del estado
     */
    public function getStateNameAttribute()
    {
        return $this->state?->default_name ?? '-';
    }

    /**
     * Accesor para el nombre del país
     */
    public function getCountryNameAttribute()
    {
        return $this->country?->name ?? '-';
    }
}
