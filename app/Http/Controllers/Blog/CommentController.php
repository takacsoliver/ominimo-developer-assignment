<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Http\Controllers\Blog;

use OminimoBlog\Contracts\CommentServiceInterface;
use OminimoBlog\Contracts\PostServiceInterface;
use OminimoBlog\Data\Blog\CommentData;
use OminimoBlog\Http\Controllers\Controller;
use OminimoBlog\Http\Requests\Blog\AdminDeleteCommentRequest;
use OminimoBlog\Http\Requests\Blog\DeleteCommentRequest;
use OminimoBlog\Http\Requests\Blog\StoreCommentRequest;
use OminimoBlog\Models\Comment;
use OminimoBlog\Models\Post;
use Illuminate\Http\RedirectResponse;

final class CommentController extends Controller
{
    public function __construct(
        private readonly CommentServiceInterface $commentService,
        private readonly PostServiceInterface $postService
    ) {}

    public function store(StoreCommentRequest $request, Post $post): RedirectResponse
    {
        $commentData = CommentData::from($request->validated());
        $this->commentService->createComment($commentData, $post, auth()->user());

        return redirect()->back()->with('success', __('messages.success.comment_added'));
    }

    public function destroy(DeleteCommentRequest $request, Post $post, Comment $comment): RedirectResponse
    {
        $this->commentService->deleteComment($comment);

        return redirect()->back()->with('success', __('messages.success.comment_deleted'));
    }

    public function adminDestroy(AdminDeleteCommentRequest $request, Comment $comment): RedirectResponse
    {
        $this->commentService->deleteComment($comment);

        return redirect()->back()->with('success', __('messages.success.comment_deleted_by_admin'));
    }

}
