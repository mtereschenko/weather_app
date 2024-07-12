<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/token', [AuthController::class, 'generateToken'])->name("auth.generateToken");
