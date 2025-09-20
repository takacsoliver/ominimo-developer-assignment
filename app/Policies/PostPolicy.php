<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Policies;

use OminimoBlog\Models\Post;
use OminimoBlog\Models\User;
use OminimoBlog\Enums\Permission;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Post $post): bool
    {
        return true;
    }

    public function create(?User $user): bool
    {
        return $user?->hasPermissionTo(Permission::CREATE_POSTS->value) ?? false;
    }

    public function update(?User $user, Post $post): bool
    {
        return $user?->id === $post->user_id || $user?->hasPermissionTo(Permission::EDIT_ANY_POST->value) ?? false;
    }

    public function delete(?User $user, Post $post): bool
    {
        return $user?->id === $post->user_id || $user?->hasPermissionTo(Permission::DELETE_ANY_POST->value) ?? false;
    }

    public function deleteAny(?User $user): bool
    {
        return $user?->hasPermissionTo(Permission::DELETE_ANY_POST->value) ?? false;
    }

    public function restore(User $user, Post $post): bool
    {
        return false;
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return false;
    }
}
