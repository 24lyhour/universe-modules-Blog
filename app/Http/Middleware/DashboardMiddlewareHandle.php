<?php

namespace Modules\Blog\Http\Middleware;

use App\Services\MenuService;
use Closure;
use Illuminate\Http\Request;

class DashboardMiddlewareHandle
{
    protected static bool $registered = false;

    public function handle(Request $request, Closure $next)
    {
        if ($request->is('dashboard', 'dashboard/*')) {
            $this->registerMenuItems();
        }

        return $next($request);
    }

    protected function registerMenuItems(): void
    {
        if (static::$registered) {
            return;
        }

        MenuService::addMenuItem(
            'primary',
            'blog',
            __('Blog'),
            '/dashboard/blog',
            'FileText',
            70,
            'posts.view_any',
            'blog.*'
        );

        MenuService::addSubmenuItem('primary', 'blog', __('All Posts'), '/dashboard/blog', 10, 'posts.view_any', 'blog.index', 'List');
        MenuService::addSubmenuItem('primary', 'blog', __('Create Post'), '/dashboard/blog/create', 20, 'posts.create', 'blog.create', 'Plus');
        MenuService::addSubmenuItem('primary', 'blog', __('Banners'), route('blog.banners.index'), 30, 'banners.view_any', 'blog.banners.*', 'Image');
        MenuService::addSubmenuItem('primary', 'blog', __('Special Offers'), route('blog.special-offers.index'), 40, 'special_offers.view_any', 'blog.special-offers.*', 'Tag');
        MenuService::addSubmenuItem('primary', 'blog', __('Slider Shows'), route('blog.slider-shows.index'), 50, 'slider_shows.view_any', 'blog.slider-shows.*', 'PanelTop');

        static::$registered = true;
    }
}
