<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/store', [TaskController::class, 'store'])->name('tasks.store');
Route::post('/complete/{id}', [TaskController::class, 'complete'])->name('tasks.complete');
Route::delete('/delete/{id}', [TaskController::class, 'delete'])->name('tasks.delete');
