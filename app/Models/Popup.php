<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    protected $fillable = [
        'format',
        'image',
        'icon_image',
        'title',
        'subtitle',
        'message',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all active popups (for carousel/slider)
     */
    public static function getActive()
    {
        return static::where('is_active', true)->orderBy('created_at', 'asc')->get();
    }
    
    /**
     * Get single active popup (for backward compatibility)
     */
    public static function getFirstActive()
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Ensure only one popup is active (deprecated - now allows multiple active popups)
     * Kept for backward compatibility but no longer enforces single active popup
     */
    public static function ensureOnlyOneActive($excludeId = null)
    {
        // No longer enforcing single active popup - allows multiple for carousel
        // This method is kept for backward compatibility but does nothing
    }
}
