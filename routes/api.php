<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\FoodController;
use App\Http\Controllers\Api\V1\MenuController;
use App\Http\Controllers\Api\V1\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::get('menu', [MenuController::class, 'index'])->name('menu_all');
    Route::get('menu/{menu:slug}', [MenuController::class, 'item'])->name('menu_item');

    Route::get('category', [CategoryController::class, 'index'])->name('category_all');
    Route::get('category/{category:slug}', [CategoryController::class, 'item'])->name('category_item');

    Route::get('food', [FoodController::class, 'index'])->name('food_all');
    Route::get('food/{food:slug}', [FoodController::class, 'item'])->name('food_item');

    Route::get('service', [ServiceController::class, 'index'])->name('service_all');
    Route::get('service/{food:slug}', [ServiceController::class, 'item'])->name('service_item');
});
