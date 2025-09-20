<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Enums;

enum Permission: string
{
    case VIEW_POSTS = 'view-posts';
    case CREATE_POSTS = 'create-posts';
    case EDIT_ANY_POST = 'edit-any-post';
    case DELETE_ANY_POST = 'delete-any-post';
    case VIEW_COMMENTS = 'view-comments';
    case CREATE_COMMENTS = 'create-comments';
    case EDIT_ANY_COMMENT = 'edit-any-comment';
    case DELETE_ANY_COMMENT = 'delete-any-comment';
    case MANAGE_COMMENTS = 'manage-comments';
    case MANAGE_USERS = 'manage-users';
    case MANAGE_ROLES = 'manage-roles';
}
