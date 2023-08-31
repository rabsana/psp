<?php

namespace Rabsana\Psp\Models;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    // Use modules, traits, plugins ...


    // Config the model
    protected $guarded = ['id'];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return config('rabsana-psp.database.merchants.table', 'merchants');
    }

    // Filters
    public function scopeUserId($query, $userId = null)
    {
        if (filled($userId)) {
            $query->where('user_id', $userId);
        }
        return $query;
    }

    public function scopeName($query, $name = null)
    {
        if (filled($name)) {
            $query->where('name', 'LIKE', "%$name%");
        }
        return $query;
    }

    public function scopeToken($query, $token = null)
    {
        if (filled($token)) {
            $query->where('token', $token);
        }
        return $query;
    }

    public function scopeActive($query)
    {
        return $query->isActive(1);
    }

    public function scopeInActive($query)
    {
        return $query->isActive(0);
    }

    public function scopeIsActive($query, $isActive = null)
    {
        if (filled($isActive)) {
            $query->where('is_active', $isActive);
        }
        return $query;
    }


    // Relations

    public function user()
    {
        return $this->belongsTo(config('rabsana-psp.modelRelations.user', 'App\\Model\\User'));
    }

    public function currencies()
    {
        return $this->belongsToMany(config('rabsana-psp.modelRelations.currency', 'App\\Model\\Currency'))->withTimestamps();
    }



    // Accessors

    public function getLogoAttribute($value)
    {
        return $value ?: '';
    }

    public function getLogoPathAttribute()
    {
        return ($this->logo) ? asset($this->logo) : "";
    }

    public function getIsActivePrettifiedAttribute()
    {
        return ($this->is_active) ? "فعال" : "غیر فعال";
    }


    // Mutators


    // Extra methods
}
