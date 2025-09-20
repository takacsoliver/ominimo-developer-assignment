<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Policies;

use OminimoBlog\Models\Comment;
use OminimoBlog\Models\User;
use OminimoBlog\Enums\Permission;

class CommentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Comment $comment): bool
    {
        return true;
    }

    public function create(?User $user): bool
    {
        return true;
    }

    public function update(?User $user, Comment $comment): bool
    {
        if (!$user) return false;
        return $user->id === $comment->user_id || $user->hasPermissionTo(Permission::EDIT_ANY_COMMENT->value);
    }

    public function delete(?User $user, Comment $comment): bool
    {
        if (!$user) return false;
        return $user->id === $comment->user_id || $user->hasPermissionTo(Permission::DELETE_ANY_COMMENT->value);
    }

    public function deleteAny(?User $user): bool
    {
        return $user?->hasPermissionTo(Permission::DELETE_ANY_COMMENT->value) ?? false;
    }

    public function approve(?User $user, Comment $comment): bool
    {
        return $user?->hasPermissionTo(Permission::MANAGE_COMMENTS->value) ?? false;
    }

    public function restore(User $user, Comment $comment): bool
    {
        return false;
    }

    public function forceDelete(User $user, Comment $comment): bool
    {
        return false;
    }
}
