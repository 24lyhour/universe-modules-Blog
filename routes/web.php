<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\Dashboard\V1\BlogController;

Route::middleware(['auth', 'verified', 'module:blog'])->group(function () {
    Route::resource('blogs', BlogController::class)->names('blog');
});
