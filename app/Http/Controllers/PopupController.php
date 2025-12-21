<?php

namespace App\Http\Controllers;

use App\Models\Popup;
use Illuminate\Http\Request;

class PopupController extends Controller
{
    /**
     * Get active popup data for frontend
     */
    public function getActive()
    {
        $popup = Popup::getActive();
        
        if ($popup) {
            return response()->json([
                'success' => true,
                'data' => [
                    'image' => $popup->image ? asset('storage/' . $popup->image) : null,
                    'icon_image' => $popup->icon_image ? asset('storage/' . $popup->icon_image) : null,
                    'title' => $popup->title,
                    'message' => $popup->message,
                ]
            ]);
        }
        
        return response()->json([
            'success' => false,
            'data' => null
        ]);
    }
}

