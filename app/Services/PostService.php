<?php

namespace Modules\Blog\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Modules\Blog\Contracts\PostRepositoryInterface;
use Modules\Blog\Models\Post;

class PostService
{
    public function __construct(
        protected PostRepositoryInterface $postRepository
    ) {}

    /**
     * Get all posts.
     */
    public function getAllPosts(): Collection
    {
        return $this->postRepository->all();
    }

    /**
     * Get paginated posts.
     */
    public function getPaginatedPosts(int $perPage = 15): LengthAwarePaginator
    {
        return $this->postRepository->paginate($perPage);
    }

    /**
     * Get a post by ID.
     */
    public function getPostById(int $id): ?Post
    {
        return $this->postRepository->find($id);
    }

    /**
     * Get a post by slug.
     */
    public function getPostBySlug(string $slug): ?Post
    {
        return $this->postRepository->findBySlug($slug);
    }

    /**
     * Create a new post.
     */
    public function createPost(array $data): Post
    {
        $data['slug'] = $this->generateUniqueSlug($data['title']);
        $data['user_id'] = auth()->id();

        if (isset($data['status']) && $data['status'] === 'published' && ! isset($data['published_at'])) {
            $data['published_at'] = now();
        }

        return $this->postRepository->create($data);
    }

    /**
     * Update a post.
     */
    public function updatePost(int $id, array $data): bool
    {
        if (isset($data['title'])) {
            $post = $this->postRepository->find($id);
            if ($post && $post->title !== $data['title']) {
                $data['slug'] = $this->generateUniqueSlug($data['title'], $id);
            }
        }

        if (isset($data['status']) && $data['status'] === 'published') {
            $post = $post ?? $this->postRepository->find($id);
            if ($post && ! $post->published_at) {
                $data['published_at'] = now();
            }
        }

        return $this->postRepository->update($id, $data);
    }

    /**
     * Delete a post.
     */
    public function deletePost(int $id): bool
    {
        return $this->postRepository->delete($id);
    }

    /**
     * Get published posts.
     */
    public function getPublishedPosts(int $perPage = 15): LengthAwarePaginator
    {
        return $this->postRepository->getPublished($perPage);
    }

    /**
     * Get draft posts.
     */
    public function getDraftPosts(int $perPage = 15): LengthAwarePaginator
    {
        return $this->postRepository->getDrafts($perPage);
    }

    /**
     * Get posts by user.
     */
    public function getPostsByUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->postRepository->getByUserId($userId, $perPage);
    }

    /**
     * Generate a unique slug for the post.
     */
    protected function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (true) {
            $existingPost = $this->postRepository->findBySlug($slug);

            if (! $existingPost || ($excludeId && $existingPost->id === $excludeId)) {
                break;
            }

            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}
