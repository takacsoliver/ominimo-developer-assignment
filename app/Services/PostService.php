<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Services;

use OminimoBlog\Contracts\PostServiceInterface;
use OminimoBlog\Data\Blog\PostData;
use OminimoBlog\Models\Post;
use OminimoBlog\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;

final class PostService implements PostServiceInterface
{
    public function __construct(
        private readonly Post $post
    ) {}

    public function getPosts(int $perPage = 12): LengthAwarePaginator
    {
        if (Auth::check()) {
            $perPage = 11;
        }
        
        return $this->post
            ->with(['user', 'comments'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getPostsLimit(int $limit = 6): Collection
    {
        return $this->post
            ->with(['user', 'comments'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getPostById(int $id): ?Post
    {
        return $this->post
            ->with(['user.roles', 'comments.user'])
            ->find($id);
    }

    public function createPost(PostData $data, User $user): Post
    {
        return DB::transaction(function () use ($data, $user) {
            return $this->post->create([
                'title' => $data->title,
                'content' => $data->content,
                'slug' => $this->generateUniqueSlug($data->title),
                'user_id' => $user->id,
            ]);
        });
    }

    public function updatePost(Post $post, PostData $data): Post
    {
        return DB::transaction(function () use ($post, $data) {
            $post->update([
                'title' => $data->title,
                'content' => $data->content,
                'slug' => $this->generateUniqueSlug($data->title, $post->id),
            ]);

            return $post->fresh(['user', 'comments.user']);
        });
    }

    public function deletePost(Post $post): bool
    {
        return DB::transaction(function () use ($post) {
            return $post->delete();
        });
    }

    public function getUserPosts(User $user): Collection
    {
        return $user->posts()
            ->with(['comments'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    private function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while ($this->slugExists($slug, $excludeId)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $query = $this->post->where('slug', $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
