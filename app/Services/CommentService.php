<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Services;

use OminimoBlog\Contracts\CommentServiceInterface;
use OminimoBlog\Data\Blog\CommentData;
use OminimoBlog\Models\Comment;
use OminimoBlog\Models\Post;
use OminimoBlog\Models\User;
use Illuminate\Database\Eloquent\Collection;

final class CommentService implements CommentServiceInterface
{
    public function __construct(
        private readonly Comment $comment
    ) {}

    public function createComment(CommentData $data, Post $post, ?User $user = null): Comment
    {
        return $this->comment->create([
            'post_id' => $post->id,
            'user_id' => $user?->id,
            'guest_name' => $data->guest_name ?? null,
            'guest_email' => $data->guest_email ?? null,
            'comment' => $data->comment,
        ]);
    }

    public function deleteComment(Comment $comment): bool
    {
        return $comment->delete();
    }

    public function getPostComments(Post $post): Collection
    {
        return $post->comments()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
