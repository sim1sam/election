<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voter;
use App\Models\Popup;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'voters_total' => Voter::count(),
            'popups_active' => Popup::where('is_active', true)->count(),
            'popups_total' => Popup::count(),
            'users_total' => User::count(),
            'admins_total' => User::where('role', 'admin')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
