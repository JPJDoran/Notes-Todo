<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\HomeController;

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/{categoryId?}', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Update routes
    Route::post('/todo/toggleItemCompleteStatus', [HomeController::class, 'toggleItemCompleteStatus']);
    Route::post('/todo/editCategory', [HomeController::class, 'editCategory']);
    Route::post('/todo/editList', [HomeController::class, 'editList']);
    Route::post('/todo/editItem', [HomeController::class, 'editItem']);

    // Add routes
    Route::post('/todo/addItem', [HomeController::class, 'addItem']);
    Route::post('/todo/addList', [HomeController::class, 'addList']);
    Route::post('/todo/addCategory', [HomeController::class, 'addCategory']);

    // Get partial routes
    Route::post('/todo/getListItems', [HomeController::class, 'getListItems']);
    Route::post('/todo/getCategoryLists', [HomeController::class, 'getCategoryLists']);
    Route::get('/todo/getCategory/{id}', [HomeController::class, 'getCategory']);
    Route::get('/todo/getList/{id}', [HomeController::class, 'getList']);
    Route::get('/todo/getItem/{id}', [HomeController::class, 'getItem']);
});
