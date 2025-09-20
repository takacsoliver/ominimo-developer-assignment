<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Contracts;

use OminimoBlog\Data\Blog\CommentData;
use OminimoBlog\Models\Comment;
use OminimoBlog\Models\Post;
use OminimoBlog\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface CommentServiceInterface
{
    public function createComment(CommentData $data, Post $post, User $user): Comment;

    public function deleteComment(Comment $comment): bool;

    public function getPostComments(Post $post): Collection;
}
