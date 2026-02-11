<?php

namespace Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Blog\Http\Requests\StorePostRequest;
use Modules\Blog\Http\Requests\UpdatePostRequest;
use Modules\Blog\Services\PostService;

class BlogController extends Controller
{
    public function __construct(
        protected PostService $postService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $posts = $this->postService->getPaginatedPosts();

        return Inertia::render('blog::Index', [
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('blog::Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): RedirectResponse
    {
        $this->postService->createPost($request->validated());

        return redirect()
            ->route('blog.index')
            ->with('success', 'Post created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show(int $id): Response
    {
        $post = $this->postService->getPostById($id);

        if (! $post) {
            abort(404);
        }

        return Inertia::render('blog::Show', [
            'post' => $post,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): Response
    {
        $post = $this->postService->getPostById($id);

        if (! $post) {
            abort(404);
        }

        return Inertia::render('blog::Edit', [
            'post' => $post,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, int $id): RedirectResponse
    {
        $updated = $this->postService->updatePost($id, $request->validated());

        if (! $updated) {
            abort(404);
        }

        return redirect()
            ->route('blog.index')
            ->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $deleted = $this->postService->deletePost($id);

        if (! $deleted) {
            abort(404);
        }

        return redirect()
            ->route('blog.index')
            ->with('success', 'Post deleted successfully.');
    }
}
