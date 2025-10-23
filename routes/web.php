<?php


use Illuminate\Support\Facades\Route;


Route::get('/', [App\Http\Controllers\LampController::class, 'index'])->name('lamps.index');

Route::post('/lamps/{id}', [App\Http\Controllers\LampController::class, 'updateLamp'])->name('lamps.update');
