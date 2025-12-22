<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QRCode extends Model
{
    protected $table = 'qr_codes';

    protected $fillable = [
        'url',
        'size',
        'format',
        'file_path',
        'svg_content',
        'title',
        'scan_count',
    ];

    protected $casts = [
        'size' => 'integer',
        'scan_count' => 'integer',
    ];
    
    /**
     * Increment scan count
     */
    public function incrementScan()
    {
        $this->increment('scan_count');
    }
}
