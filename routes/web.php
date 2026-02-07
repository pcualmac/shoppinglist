<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Livewire\Auth\Login;
use App\Livewire\ShoppingListPage;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', Login::class)->name('login');

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/list', ShoppingListPage::class)
    ->middleware('auth')
    ->name('shopping.list');
