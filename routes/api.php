<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\PortController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/countries', [CountryController::class, 'index']);
Route::get('/ports', [PortController::class, 'index']);
Route::get('/news', [\App\Http\Controllers\Api\NewsController::class, 'index']);
Route::get('/currency', [\App\Http\Controllers\Api\CurrencyController::class, 'index']);
Route::get('/risk', [\App\Http\Controllers\Api\RiskController::class, 'index']);