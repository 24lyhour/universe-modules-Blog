<?php

namespace Modules\Blog\Http\Controllers\Api\V1\Customer\Banner;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Blog\Http\Resources\Api\V1\BannerResource;
use Modules\Blog\Models\Banner;
use Modules\Blog\Services\BannerService;

class BannerPublicController extends Controller
{
    public function __construct(
        protected BannerService $bannerService
    ) {}

    /**
     * List active banners (for mobile/frontend).
     */
    public function index(Request $request): JsonResponse
    {
        $banners = Banner::active()
            ->scheduled()
            ->orderBy('created_at', 'desc')
            ->paginate($request->integer('per_page', 15));

        return response()->json(
            BannerResource::collection($banners)->response()->getData(true)
        );
    }

    /**
     * Get a single banner by UUID.
     */
    public function show(string $identifier): JsonResponse
    {
        $banner = Banner::active()
            ->where('uuid', $identifier)
            ->firstOrFail();

        return response()->json(['data' => new BannerResource($banner)]);
    }

    /**
     * Special offers (latest active banners limited).
     */
    public function specialOffers(Request $request): JsonResponse
    {
        $banners = Banner::active()
            ->scheduled()
            ->orderBy('created_at', 'desc')
            ->limit($request->integer('limit', 4))
            ->get();

        return response()->json(['data' => BannerResource::collection($banners)]);
    }
}
