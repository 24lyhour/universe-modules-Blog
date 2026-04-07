<?php

namespace Modules\Blog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Blog\Models\SpacialOffer;

class SpecialOfferSeeder extends Seeder
{
    public function run(): void
    {
        $offers = [
            [
                'title' => 'Exclusive Deals',
                'subtitle' => 'Up to 50% off',
                'icon' => 'local_offer_rounded',
                'gradient_start' => '#3BC472',
                'gradient_end' => '#2A9D5C',
                'button_text' => 'Shop Now',
                'deeplink' => null,
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'title' => 'Free Shipping',
                'subtitle' => 'On orders over $25',
                'icon' => 'local_shipping_rounded',
                'gradient_start' => '#5B7FFF',
                'gradient_end' => '#3B5EE0',
                'button_text' => 'Order Now',
                'deeplink' => null,
                'is_active' => true,
                'sort_order' => 1,
            ],
        ];

        foreach ($offers as $offer) {
            SpacialOffer::firstOrCreate(
                ['title' => $offer['title']],
                $offer
            );
        }

        $this->command->info('Special offers seeded: ' . count($offers));
    }
}
