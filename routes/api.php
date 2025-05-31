<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

Route::prefix('recipes')->group(function () {
    Route::get('/', [RecipeController::class, 'index'])->name('recipes.index');
    Route::get('/search', [RecipeController::class, 'search'])->name('recipes.search');
    Route::get('/category/{categoryId}', [RecipeController::class, 'byCategory'])->name('recipes.byCategory');
    Route::get('/{id}', [RecipeController::class, 'show'])->name('recipes.show');

    Route::middleware('auth.api')->group(function () {
        Route::post('/', [RecipeController::class, 'store'])->name('recipes.store');
        Route::put('/{id}', [RecipeController::class, 'update'])->name('recipes.update');
        Route::delete('/{id}', [RecipeController::class, 'destroy'])->name('recipes.destroy');
    });
});


Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('categories.index');

    Route::middleware('auth.api')->group(function () {
        Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });
});
