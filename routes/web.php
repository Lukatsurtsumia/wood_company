<?php

use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

// English stays on the bare domain so the existing canonical URL never moves.
Route::view('/', 'welcome')->name('home');

// Each language also gets a real, crawlable URL (/fr, /ru). Without these the
// translations lived only behind a session cookie, so Googlebot - which keeps no
// session - could only ever see the English page.
Route::get('/{locale}', function (string $locale) {
    session(['locale' => $locale]);
    app()->setLocale($locale);

    return view('welcome');
})->whereIn('locale', SetLocale::SUPPORTED)->name('home.locale');

// Switch language and return to the page the visitor came from.
Route::get('lang/{locale}', function (string $locale) {
    if (in_array($locale, SetLocale::SUPPORTED, true)) {
        session(['locale' => $locale]);
    }

    return back();
})->name('lang.switch');
