<?php

namespace Modules\Blog\Services\Dashboard\V1;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Modules\Blog\Models\SpacialOffer;

class SpecialOfferService
{
    public function paginate(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $query = SpacialOffer::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('subtitle', 'like', "%{$search}%");
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

    public function create(array $data): SpacialOffer
    {
        $data['created_by'] = auth()->id();

        return SpacialOffer::create($data);
    }

    public function update(SpacialOffer $offer, array $data): SpacialOffer
    {
        $data['updated_by'] = auth()->id();
        $offer->update($data);

        return $offer->fresh();
    }

    public function delete(SpacialOffer $offer): bool
    {
        return $offer->delete();
    }

    public function toggleActive(SpacialOffer $offer, ?bool $status = null): SpacialOffer
    {
        $offer->update([
            'is_active' => $status ?? !$offer->is_active,
            'updated_by' => auth()->id(),
        ]);

        return $offer->fresh();
    }

    public function getStats(): array
    {
        return Cache::remember('blog_special_offer_stats', 300, function () {
            return [
                'total' => SpacialOffer::count(),
                'active' => SpacialOffer::where('is_active', true)->count(),
                'inactive' => SpacialOffer::where('is_active', false)->count(),
            ];
        });
    }
}
