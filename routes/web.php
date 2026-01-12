<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AdminController;

Route::get('/', [ClientController::class, 'index'])->name('client.home');
Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

