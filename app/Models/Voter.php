<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Voter extends Model
{
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $fillable = [
        'name',
        'voter_number',
        'father_name',
        'mother_name',
        'occupation',
        'address',
        'polling_center_name',
        'ward_number',
        'voter_area_number',
        'voter_serial_number',
        'date_of_birth',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Get the next available sequential ID starting from 1
     */
    public static function getNextSequentialId()
    {
        // Get all existing IDs
        $existingIds = static::pluck('id')->sort()->values()->toArray();
        
        // Find the first gap starting from 1
        $expectedId = 1;
        foreach ($existingIds as $existingId) {
            if ($existingId == $expectedId) {
                $expectedId++;
            } else {
                break;
            }
        }
        
        return $expectedId;
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($voter) {
            if (!$voter->id) {
                $voter->id = static::getNextSequentialId();
            }
        });
    }
}
