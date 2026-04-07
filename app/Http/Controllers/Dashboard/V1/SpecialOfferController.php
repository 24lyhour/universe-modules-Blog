<?php

namespace Modules\Blog\Http\Controllers\Dashboard\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Blog\Http\Requests\Dashboard\V1\SpecialOfferRequest\StoreSpecialOfferRequest;
use Modules\Blog\Http\Requests\Dashboard\V1\SpecialOfferRequest\UpdateSpecialOfferRequest;
use Modules\Blog\Http\Resources\Dashboard\V1\SpecialOfferResource;
use Modules\Blog\Models\SpacialOffer;
use Modules\Blog\Services\Dashboard\V1\SpecialOfferService;
use Momentum\Modal\Modal;

class SpecialOfferController extends Controller
{
    public function __construct(
        protected SpecialOfferService $specialOfferService
    ) {
    }

    public function index(Request $request): Response
    {
        $filters = $request->only(['search', 'is_active']);
        $perPage = $request->integer('per_page', 10);

        $offers = $this->specialOfferService->paginate($perPage, $filters);

        return Inertia::render('blog::Dashboard/V1/SpecialOffer/Index', [
            'specialOffers' => SpecialOfferResource::collection($offers)->response()->getData(true),
            'filters' => $filters,
            'stats' => $this->specialOfferService->getStats(),
        ]);
    }

    public function create(): Modal
    {
        return Inertia::modal('blog::Dashboard/V1/SpecialOffer/Create', [])
            ->baseRoute('blog.special-offers.index');
    }

    public function store(StoreSpecialOfferRequest $request): RedirectResponse
    {
        $this->specialOfferService->create($request->validated());

        return redirect()->route('blog.special-offers.index')
            ->with('success', 'Special offer created successfully.');
    }

    public function show(SpacialOffer $specialOffer): Response
    {
        return Inertia::render('blog::Dashboard/V1/SpecialOffer/Show', [
            'specialOffer' => (new SpecialOfferResource($specialOffer))->resolve(),
        ]);
    }

    public function edit(SpacialOffer $specialOffer): Modal
    {
        return Inertia::modal('blog::Dashboard/V1/SpecialOffer/Edit', [
            'specialOffer' => (new SpecialOfferResource($specialOffer))->resolve(),
        ])->baseRoute('blog.special-offers.index');
    }

    public function update(UpdateSpecialOfferRequest $request, SpacialOffer $specialOffer): RedirectResponse
    {
        $this->specialOfferService->update($specialOffer, $request->validated());

        return redirect()->route('blog.special-offers.index')
            ->with('success', 'Special offer updated successfully.');
    }

    public function destroy(SpacialOffer $specialOffer): RedirectResponse
    {
        $this->specialOfferService->delete($specialOffer);

        return redirect()->route('blog.special-offers.index')
            ->with('success', 'Special offer deleted successfully.');
    }

    public function toggleActive(Request $request, SpacialOffer $specialOffer): RedirectResponse
    {
        $status = $request->has('status') ? $request->boolean('status') : null;
        $this->specialOfferService->toggleActive($specialOffer, $status);

        return redirect()->back()
            ->with('success', 'Special offer status updated.');
    }
}
