<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voter extends Model
{
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
