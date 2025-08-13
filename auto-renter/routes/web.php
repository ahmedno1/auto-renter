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


Route::get('/', function () {
    return view('welcome');
})->name('');

Route::middleware(['auth', 'verified', 'role:owner'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('cars', Cars::class)->name('cars');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('home', Home::class)->name('home');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';

Route::fallback(function () {
    return redirect()->route('home');
});
