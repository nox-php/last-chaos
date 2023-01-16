<?php

use Illuminate\Support\Facades\Route;
use Nox\LastChaos\Http\Controllers\InventoryBackgroundController;
use Nox\LastChaos\Http\Controllers\InventoryBagIconController;
use Nox\LastChaos\Http\Controllers\ItemIconController;

Route::middleware('web')->group(static function () {
    Route::get('/last-chaos/item/icon/{id}', ItemIconController::class)
        ->name('last-chaos.item.icon');

    Route::get('/last-chaos/inventory/{class}', InventoryBackgroundController::class)
        ->name('last-chaos.inventory');

    Route::get('/last-chaos/buttons/{type}', InventoryBagIconController::class)
        ->name('last-chaos.buttons');
});