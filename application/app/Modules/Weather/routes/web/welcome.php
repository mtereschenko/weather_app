<?php


use App\Modules\Weather\Http\Controllers\Web\WelcomeController;
use Illuminate\Support\Facades\Route;

$namePrefix = 'weather';

Route::get('/', [WelcomeController::class, 'welcome'])->name("{$namePrefix}.web.welcome");
