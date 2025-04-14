<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpotifyController;

// Web routes
Route::get('/', [SpotifyController::class, 'welcome'])->name('home');
