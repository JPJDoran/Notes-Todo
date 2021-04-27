<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\HomeController;

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/{categoryId?}', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::post('/todo/toggleItemCompleteStatus', [HomeController::class, 'toggleItemCompleteStatus']);
    Route::post('/todo/addItem', [HomeController::class, 'addItem']);
    Route::post('/todo/getListItems', [HomeController::class, 'getListItems']);
    Route::post('/todo/addList', [HomeController::class, 'addList']);
    Route::post('/todo/getCategoryLists', [HomeController::class, 'getCategoryLists']);
    Route::post('/todo/addCategory', [HomeController::class, 'addCategory']);
});
