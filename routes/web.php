<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome')->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Public catalog
Volt::route('products', 'pages.products.index')->name('products.index');
Volt::route('products/{product}', 'pages.products.show')->name('products.show');

// Admin catalog management
Route::middleware(['auth', 'verified'])->group(function () {
    Volt::route('admin/products', 'pages.admin.products.index')->name('admin.products.index');
    Volt::route('admin/products/create', 'pages.admin.products.create')->name('admin.products.create');
    Volt::route('admin/products/{product}/edit', 'pages.admin.products.edit')->name('admin.products.edit');
});

require __DIR__.'/auth.php';
