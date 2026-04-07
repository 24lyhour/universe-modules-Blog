<?php

namespace Modules\Blog\Http\Controllers\Api\V1\Customer\SliderShow;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Blog\Http\Resources\Api\V1\SliderShowResource;
use Modules\Blog\Models\SliderShow;

class SliderShowPublicController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $sliders = SliderShow::active()
            ->scheduled()
            ->ordered()
            ->limit($request->integer('limit', 10))
            ->get();

        return response()->json(['data' => SliderShowResource::collection($sliders)]);
    }

    public function show(string $identifier): JsonResponse
    {
        $slider = SliderShow::active()
            ->where('uuid', $identifier)
            ->firstOrFail();

        return response()->json(['data' => new SliderShowResource($slider)]);
    }
}
