<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    protected $fillable = [
        'image',
        'icon_image',
        'title',
        'message',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the active popup
     */
    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Ensure only one popup is active
     */
    public static function ensureOnlyOneActive($excludeId = null)
    {
        $query = static::where('is_active', true);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        $query->update(['is_active' => false]);
    }
}
