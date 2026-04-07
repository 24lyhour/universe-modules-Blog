<?php

namespace Modules\Blog\Services\Dashboard\V1;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Modules\Blog\Models\Banner;

class BannerService
{
    public function paginate(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $query = Banner::query();

        if (!empty($filters['search'])) {
            $query->where('name', 'like', "%{$filters['search']}%");
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', $filters['is_active'] === 'true' || $filters['is_active'] === true);
        }

        return $query
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data): Banner
    {
        $data['created_by'] = auth()->id();

        return Banner::create($data);
    }

    public function update(Banner $banner, array $data): Banner
    {
        $data['updated_by'] = auth()->id();

        $banner->update($data);

        return $banner->fresh();
    }

    public function delete(Banner $banner): bool
    {
        return $banner->delete();
    }

    public function toggleActive(Banner $banner, ?bool $status = null): Banner
    {
        $banner->update([
            'is_active' => $status ?? !$banner->is_active,
            'updated_by' => auth()->id(),
        ]);

        return $banner->fresh();
    }

    public function getStats(): array
    {
        return Cache::remember('blog_banner_stats', 300, function () {
            return [
                'total' => Banner::count(),
                'active' => Banner::where('is_active', true)->count(),
                'inactive' => Banner::where('is_active', false)->count(),
            ];
        });
    }

    public function clearStatsCache(): void
    {
        Cache::forget('blog_banner_stats');
    }
}
