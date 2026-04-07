<?php

namespace Modules\Blog\Http\Controllers\Dashboard\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Blog\Http\Requests\Dashboard\V1\SliderShowRequest\StoreSliderShowRequest;
use Modules\Blog\Http\Requests\Dashboard\V1\SliderShowRequest\UpdateSliderShowRequest;
use Modules\Blog\Http\Resources\Dashboard\V1\SliderShowResource;
use Modules\Blog\Models\SliderShow;
use Modules\Blog\Services\Dashboard\V1\SliderShowService;
use Momentum\Modal\Modal;

class SliderShowController extends Controller
{
    public function __construct(
        protected SliderShowService $sliderShowService
    ) {
    }

    public function index(Request $request): Response
    {
        $filters = $request->only(['search', 'is_active']);
        $perPage = $request->integer('per_page', 10);

        $sliders = $this->sliderShowService->paginate($perPage, $filters);

        return Inertia::render('blog::Dashboard/V1/SliderShow/Index', [
            'sliderShows' => SliderShowResource::collection($sliders)->response()->getData(true),
            'filters' => $filters,
            'stats' => $this->sliderShowService->getStats(),
        ]);
    }

    public function create(): Modal
    {
        return Inertia::modal('blog::Dashboard/V1/SliderShow/Create', [])
            ->baseRoute('blog.slider-shows.index');
    }

    public function store(StoreSliderShowRequest $request): RedirectResponse
    {
        $this->sliderShowService->create($request->validated());

        return redirect()->route('blog.slider-shows.index')
            ->with('success', 'Slider show created successfully.');
    }

    public function show(SliderShow $sliderShow): Response
    {
        return Inertia::render('blog::Dashboard/V1/SliderShow/Show', [
            'sliderShow' => (new SliderShowResource($sliderShow))->resolve(),
        ]);
    }

    public function edit(SliderShow $sliderShow): Modal
    {
        return Inertia::modal('blog::Dashboard/V1/SliderShow/Edit', [
            'sliderShow' => (new SliderShowResource($sliderShow))->resolve(),
        ])->baseRoute('blog.slider-shows.index');
    }

    public function update(UpdateSliderShowRequest $request, SliderShow $sliderShow): RedirectResponse
    {
        $this->sliderShowService->update($sliderShow, $request->validated());

        return redirect()->route('blog.slider-shows.index')
            ->with('success', 'Slider show updated successfully.');
    }

    public function destroy(SliderShow $sliderShow): RedirectResponse
    {
        $this->sliderShowService->delete($sliderShow);

        return redirect()->route('blog.slider-shows.index')
            ->with('success', 'Slider show deleted successfully.');
    }

    public function toggleActive(Request $request, SliderShow $sliderShow): RedirectResponse
    {
        $status = $request->has('status') ? $request->boolean('status') : null;
        $this->sliderShowService->toggleActive($sliderShow, $status);

        return redirect()->back()
            ->with('success', 'Slider show status updated.');
    }
}
