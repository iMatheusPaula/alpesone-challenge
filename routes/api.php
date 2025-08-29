<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;

Route::apiResource('cars', CarController::class);
