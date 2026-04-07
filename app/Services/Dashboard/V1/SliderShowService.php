<?php

namespace Modules\Blog\Services\Dashboard\V1;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Modules\Blog\Models\SliderShow;

class SliderShowService
{
    public function paginate(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $query = SliderShow::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '' && $filters['is_active'] !== 'all') {
            $query->where('is_active', $filters['is_active'] === 'true' || $filters['is_active'] === true);
        }

        return $query
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data): SliderShow
    {
        $data['created_by'] = auth()->id();
        return SliderShow::create($data);
    }

    public function update(SliderShow $sliderShow, array $data): SliderShow
    {
        $data['updated_by'] = auth()->id();
        $sliderShow->update($data);
        return $sliderShow->fresh();
    }

    public function delete(SliderShow $sliderShow): bool
    {
        return $sliderShow->delete();
    }

    public function toggleActive(SliderShow $sliderShow, ?bool $status = null): SliderShow
    {
        $sliderShow->update([
            'is_active' => $status ?? !$sliderShow->is_active,
            'updated_by' => auth()->id(),
        ]);
        return $sliderShow->fresh();
    }

    public function getStats(): array
    {
        return Cache::remember('blog_slider_show_stats', 300, function () {
            return [
                'total' => SliderShow::count(),
                'active' => SliderShow::where('is_active', true)->count(),
                'inactive' => SliderShow::where('is_active', false)->count(),
            ];
        });
    }
}
