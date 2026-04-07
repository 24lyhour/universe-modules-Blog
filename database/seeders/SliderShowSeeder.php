<?php

namespace Modules\Blog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Blog\Models\SliderShow;

class SliderShowSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'title' => 'Exclusive Deals!',
                'description' => 'Get up to 50% discount on summer essentials.',
                'button_text' => 'Shop Now',
                'icon' => 'flash_on',
                'gradient_start' => '#3BC472',
                'gradient_end' => '#2A9D5C',
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'title' => 'Free Shipping',
                'description' => 'On all orders over $50 this weekend only.',
                'button_text' => 'Order Now',
                'icon' => 'local_shipping',
                'gradient_start' => '#3BC472',
                'gradient_end' => '#2A9D5C',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'New Arrivals',
                'description' => 'Check out the latest gadgets just landed.',
                'button_text' => 'Explore',
                'icon' => 'new_releases',
                'gradient_start' => '#5B7FFF',
                'gradient_end' => '#3B5EE0',
                'is_active' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($slides as $slide) {
            SliderShow::firstOrCreate(
                ['title' => $slide['title']],
                $slide
            );
        }

        $this->command->info('Slider shows seeded: ' . count($slides));
    }
}
