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
    ];

    protected $casts = [
        'size' => 'integer',
    ];
}
