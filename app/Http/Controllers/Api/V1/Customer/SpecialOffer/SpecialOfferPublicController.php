<?php

namespace Modules\Blog\Http\Controllers\Api\V1\Customer\SpecialOffer;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Blog\Http\Resources\Api\V1\SpecialOfferResource;
use Modules\Blog\Models\SpacialOffer;

class SpecialOfferPublicController extends Controller
{
    /**
     * List active special offers for customer app.
     */
    public function index(Request $request): JsonResponse
    {
        $offers = SpacialOffer::active()
            ->scheduled()
            ->ordered()
            ->limit($request->integer('limit', 10))
            ->get();

        return response()->json(['data' => SpecialOfferResource::collection($offers)]);
    }

    /**
     * Get a single special offer by UUID.
     */
    public function show(string $identifier): JsonResponse
    {
        $offer = SpacialOffer::active()
            ->where('uuid', $identifier)
            ->firstOrFail();

        return response()->json(['data' => new SpecialOfferResource($offer)]);
    }
}
