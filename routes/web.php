<?php

use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

// Switch language and return to the page the visitor came from.
Route::get('lang/{locale}', function (string $locale) {
    if (in_array($locale, SetLocale::SUPPORTED, true)) {
        session(['locale' => $locale]);
    }

    return back();
})->name('lang.switch');
