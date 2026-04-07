<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\Api\V1\Customer\Banner\BannerPublicController;
use Modules\Blog\Http\Controllers\Api\V1\Customer\SpecialOffer\SpecialOfferPublicController;
use Modules\Blog\Http\Controllers\Api\V1\Customer\SliderShow\SliderShowPublicController;

/*
|--------------------------------------------------------------------------
| Customer App Routes
|--------------------------------------------------------------------------
*/
Route::prefix('v1/customer')->group(function () {

    // ==================== BANNERS ====================
    Route::group(['prefix' => 'banners', 'as' => 'blog.customer.banners.'], function () {
        Route::get('/', [BannerPublicController::class, 'index'])->name('index');
        Route::get('/{identifier}', [BannerPublicController::class, 'show'])->name('show');
    });

    // ==================== SPECIAL OFFERS ====================
    Route::group(['prefix' => 'special-offers', 'as' => 'blog.customer.special-offers.'], function () {
        Route::get('/', [SpecialOfferPublicController::class, 'index'])->name('index');
        Route::get('/{identifier}', [SpecialOfferPublicController::class, 'show'])->name('show');
    });

    // ==================== SLIDER SHOW ====================
    Route::group(['prefix' => 'slider-show', 'as' => 'blog.customer.slider-show.'], function () {
        Route::get('/', [SliderShowPublicController::class, 'index'])->name('index');
        Route::get('/{identifier}', [SliderShowPublicController::class, 'show'])->name('show');
    });
});
