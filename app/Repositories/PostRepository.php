<?php

namespace Modules\Blog\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Blog\Contracts\PostRepositoryInterface;
use Modules\Blog\Models\Post;

class PostRepository implements PostRepositoryInterface
{
    public function __construct(
        protected Post $model
    ) {}

    /**
     * Get all posts.
     */
    public function all(): Collection
    {
        return $this->model->with('user')->latest()->get();
    }

    /**
     * Get paginated posts.
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with('user')->latest()->paginate($perPage);
    }

    /**
     * Find a post by ID.
     */
    public function find(int $id): ?Post
    {
        return $this->model->with('user')->find($id);
    }

    /**
     * Find a post by slug.
     */
    public function findBySlug(string $slug): ?Post
    {
        return $this->model->with('user')->where('slug', $slug)->first();
    }

    /**
     * Create a new post.
     */
    public function create(array $data): Post
    {
        return $this->model->create($data);
    }

    /**
     * Update a post.
     */
    public function update(int $id, array $data): bool
    {
        $post = $this->find($id);

        if (! $post) {
            return false;
        }

        return $post->update($data);
    }

    /**
     * Delete a post.
     */
    public function delete(int $id): bool
    {
        $post = $this->find($id);

        if (! $post) {
            return false;
        }

        return $post->delete();
    }

    /**
     * Get published posts.
     */
    public function getPublished(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with('user')->published()->latest('published_at')->paginate($perPage);
    }

    /**
     * Get draft posts.
     */
    public function getDrafts(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with('user')->draft()->latest()->paginate($perPage);
    }

    /**
     * Get posts by user ID.
     */
    public function getByUserId(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with('user')->where('user_id', $userId)->latest()->paginate($perPage);
    }
}
