<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\Dashboard\V1\BannerController;
use Modules\Blog\Http\Controllers\Dashboard\V1\SpecialOfferController;
use Modules\Blog\Http\Controllers\Dashboard\V1\SliderShowController;

Route::middleware(['auth', 'verified', 'auto.permission'])
    ->prefix('dashboard')
    ->group(function () {
        // ==================== BANNER ROUTES ====================

        Route::resource('banners', BannerController::class)
            ->names('blog.banners')
            ->parameters(['banners' => 'banner']);

        Route::put('banners/{banner}/toggle-active', [BannerController::class, 'toggleActive'])
            ->name('blog.banners.toggle-active');

        // ==================== SPECIAL OFFER ROUTES ====================

        Route::resource('special-offers', SpecialOfferController::class)
            ->names('blog.special-offers')
            ->parameters(['special-offers' => 'specialOffer']);

        Route::put('special-offers/{specialOffer}/toggle-active', [SpecialOfferController::class, 'toggleActive'])
            ->name('blog.special-offers.toggle-active');

        // ==================== SLIDER SHOW ROUTES ====================

        Route::resource('slider-shows', SliderShowController::class)
            ->names('blog.slider-shows')
            ->parameters(['slider-shows' => 'sliderShow']);

        Route::put('slider-shows/{sliderShow}/toggle-active', [SliderShowController::class, 'toggleActive'])
            ->name('blog.slider-shows.toggle-active');
    });
