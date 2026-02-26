<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Livewire\Home;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\Logout;
use App\Livewire\Auth\TwoFactor;
use App\Livewire\Auth\TwoFactorAuthentication;
use App\Livewire\Cars;
use App\Livewire\Bookings;
use App\Livewire\SearchCars;
use App\Livewire\MyBookings;
use Illuminate\Support\Facades\Storage;


Route::get('/', function () {
    return redirect()->route('home');
});

Route::middleware(['auth', 'verified', 'role:owner'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('cars', Cars::class)->name('cars');
    Route::get('bookings', Bookings::class)->name('bookings');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('home', Home::class)->name('home');
    Route::get('search', SearchCars::class)->name('search');
    Route::get('my-bookings', MyBookings::class)->name('my-bookings');

});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__ . '/auth.php';

Route::get('uploads/{path}', function (string $path) {
    $path = ltrim($path, '/');

    // Only serve publicly uploaded car images.
    if (!str_starts_with($path, 'cars/')) {
        abort(404);
    }

    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }

    return Storage::disk('public')->response($path, null, [
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->where('path', '.*')->name('uploads.show');

Route::fallback(function () {
    return redirect()->route('home');
});
