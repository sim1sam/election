<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voter extends Model
{
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
     * Get the next sequential ID for bulk insert operations.
     * This ensures IDs are sequential when inserting multiple records.
     */
    public static function getNextSequentialId(): int
    {
        $maxId = static::max('id') ?? 0;
        return $maxId + 1;
    }
}
