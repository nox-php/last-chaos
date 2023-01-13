<?php

use Illuminate\Support\Facades\Route;
use Nox\LastChaos\Http\Controllers\ItemIconController;

Route::middleware('web')
    ->get('/last-chaos/item/icon/{id}', ItemIconController::class)
    ->name('last-chaos.item.icon');