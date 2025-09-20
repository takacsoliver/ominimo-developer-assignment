<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Contracts;

use OminimoBlog\Data\Blog\PostData;
use OminimoBlog\Models\Post;
use OminimoBlog\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface PostServiceInterface
{
    public function getPosts(int $perPage = 12): LengthAwarePaginator;

    public function getPostsLimit(int $limit = 6): Collection;

    public function getPostById(int $id): ?Post;

    public function createPost(PostData $data, User $user): Post;

    public function updatePost(Post $post, PostData $data): Post;

    public function deletePost(Post $post): bool;

    public function getUserPosts(User $user): Collection;
}
