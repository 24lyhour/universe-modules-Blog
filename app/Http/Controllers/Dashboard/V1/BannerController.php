<?php

namespace Modules\Blog\Http\Controllers\Dashboard\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Blog\Http\Requests\Dashboard\V1\BannerRequest\StoreBannerRequest;
use Modules\Blog\Http\Requests\Dashboard\V1\BannerRequest\UpdateBannerRequest;
use Modules\Blog\Http\Resources\Dashboard\V1\BannerResource;
use Modules\Blog\Models\Banner;
use Modules\Blog\Services\Dashboard\V1\BannerService;
use Momentum\Modal\Modal;

class BannerController extends Controller
{
    public function __construct(
        protected BannerService $bannerService
    ) {
    }

    public function index(Request $request): Response
    {
        $filters = $request->only(['search', 'is_active']);
        $perPage = $request->integer('per_page', 10);

        $banners = $this->bannerService->paginate($perPage, $filters);

        return Inertia::render('blog::Dashboard/V1/Banner/Index', [
            'bannerItems' => BannerResource::collection($banners)->response()->getData(true),
            'filters' => $filters,
            'stats' => $this->bannerService->getStats(),
        ]);
    }

    public function create(): Modal
    {
        return Inertia::modal('blog::Dashboard/V1/Banner/Create', [])
            ->baseRoute('blog.banners.index');
    }

    public function store(StoreBannerRequest $request): RedirectResponse
    {
        $this->bannerService->create($request->validated());

        return redirect()->route('blog.banners.index')
            ->with('success', 'Banner created successfully.');
    }

    public function show(Banner $banner): Response
    {
        return Inertia::render('blog::Dashboard/V1/Banner/Show', [
            'banner' => (new BannerResource($banner))->resolve(),
        ]);
    }

    public function edit(Banner $banner): Modal
    {
        return Inertia::modal('blog::Dashboard/V1/Banner/Edit', [
            'banner' => (new BannerResource($banner))->resolve(),
        ])->baseRoute('blog.banners.index');
    }

    public function update(UpdateBannerRequest $request, Banner $banner): RedirectResponse
    {
        $this->bannerService->update($banner, $request->validated());

        return redirect()->route('blog.banners.index')
            ->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $this->bannerService->delete($banner);

        return redirect()->route('blog.banners.index')
            ->with('success', 'Banner deleted successfully.');
    }

    public function toggleActive(Request $request, Banner $banner): RedirectResponse
    {
        $status = $request->has('status') ? $request->boolean('status') : null;
        $this->bannerService->toggleActive($banner, $status);

        return redirect()->back()
            ->with('success', 'Banner status updated.');
    }
}
