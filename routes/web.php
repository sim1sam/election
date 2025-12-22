<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $popup = \App\Models\Popup::getActive();
    $settings = \App\Models\HomePageSetting::getSettings();
    return view('home', compact('popup', 'settings'));
});

// QR Code scan tracking
Route::get('/qrcode/scan/{id}', [App\Http\Controllers\QRCodeScanController::class, 'track'])->name('qrcode.scan');

Route::get('/dashboard', function () {
    // Redirect admins to admin dashboard
    if (auth()->check() && auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('popups', App\Http\Controllers\Admin\PopupController::class);
    
    // QR Code Generator
    Route::get('/qrcode', [App\Http\Controllers\Admin\QRCodeController::class, 'index'])->name('qrcode.index');
    Route::post('/qrcode', [App\Http\Controllers\Admin\QRCodeController::class, 'store'])->name('qrcode.store');
    Route::delete('/qrcode/{id}', [App\Http\Controllers\Admin\QRCodeController::class, 'destroy'])->name('qrcode.destroy');
    Route::post('/qrcode/download-svg', [App\Http\Controllers\Admin\QRCodeController::class, 'downloadSVG'])->name('qrcode.download-svg');
    Route::post('/qrcode/download-png', [App\Http\Controllers\Admin\QRCodeController::class, 'downloadPNG'])->name('qrcode.download-png');
    Route::post('/qrcode/download-jpg', [App\Http\Controllers\Admin\QRCodeController::class, 'downloadJPG'])->name('qrcode.download-jpg');
    Route::get('/qrcode/preview', [App\Http\Controllers\Admin\QRCodeController::class, 'preview'])->name('qrcode.preview');
    
    // Home Page Settings
    Route::get('/home-page-settings/edit', [App\Http\Controllers\Admin\HomePageSettingController::class, 'edit'])->name('home-page-settings.edit');
    Route::put('/home-page-settings', [App\Http\Controllers\Admin\HomePageSettingController::class, 'update'])->name('home-page-settings.update');
});

require __DIR__.'/auth.php';
