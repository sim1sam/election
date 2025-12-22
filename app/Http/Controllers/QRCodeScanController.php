<?php

namespace App\Http\Controllers;

use App\Models\QRCode;
use Illuminate\Http\Request;

class QRCodeScanController extends Controller
{
    /**
     * Track QR code scan and redirect to URL
     * QR codes work unlimited times (lifetime)
     */
    public function track($id)
    {
        $qrCode = QRCode::findOrFail($id);
        
        // Increment scan count (unlimited scans - works forever)
        $qrCode->incrementScan();
        
        // Redirect to the actual URL
        return redirect($qrCode->url);
    }
}
