<?php

namespace Modules\Blog\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Blog\Models\Post;

interface PostRepositoryInterface
{
    /**
     * Get all posts.
     */
    public function all(): Collection;

    /**
     * Get paginated posts.
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a post by ID.
     */
    public function find(int $id): ?Post;

    /**
     * Find a post by slug.
     */
    public function findBySlug(string $slug): ?Post;

    /**
     * Create a new post.
     */
    public function create(array $data): Post;

    /**
     * Update a post.
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a post.
     */
    public function delete(int $id): bool;

    /**
     * Get published posts.
     */
    public function getPublished(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get draft posts.
     */
    public function getDrafts(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get posts by user ID.
     */
    public function getByUserId(int $userId, int $perPage = 15): LengthAwarePaginator;
}
