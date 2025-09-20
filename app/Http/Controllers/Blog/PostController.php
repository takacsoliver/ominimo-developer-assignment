<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Http\Controllers\Blog;

use OminimoBlog\Contracts\PostServiceInterface;
use OminimoBlog\Data\Blog\PostData;
use OminimoBlog\Http\Controllers\Controller;
use OminimoBlog\Http\Requests\Blog\AdminDeletePostRequest;
use OminimoBlog\Http\Requests\Blog\DeletePostRequest;
use OminimoBlog\Http\Requests\Blog\StorePostRequest;
use OminimoBlog\Http\Requests\Blog\UpdatePostRequest;
use OminimoBlog\Http\Resources\Blog\PostResource;
use OminimoBlog\Http\Resources\PaginationResource;
use OminimoBlog\Models\Post;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class PostController extends Controller
{
    public function __construct(
        private readonly PostServiceInterface $postService
    ) {}

    public function index(): Response
    {
        $posts = $this->postService->getPosts();

        return Inertia::render('Posts/Index', [
            'posts' => PostResource::collection($posts->items()),
            'pagination' => PaginationResource::make($posts),
        ]);
    }

    public function home(): Response
    {
        $posts = $this->postService->getPostsLimit(6);

        return Inertia::render('Home', [
            'posts' => PostResource::collection($posts),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Posts/Create');
    }

    public function store(StorePostRequest $request): RedirectResponse
    {
        $postData = PostData::from($request->validated());
        $post = $this->postService->createPost($postData, $request->user());

        return redirect()->route('posts.show', $post)
            ->with('success', __('messages.success.post_created'));
    }

    public function show(Post $post): Response
    {
        $post = $this->postService->getPostById($post->id);

        return Inertia::render('Posts/Show', [
            'post' => PostResource::make($post),
        ]);
    }

    public function edit(Post $post): Response
    {
        $post = $this->postService->getPostById($post->id);

        return Inertia::render('Posts/Edit', [
            'post' => PostResource::make($post),
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        $postData = PostData::from($request->validated());
        $post = $this->postService->updatePost($post, $postData);

        return redirect()->route('posts.show', $post)
            ->with('success', __('messages.success.post_updated'));
    }

    public function destroy(DeletePostRequest $request, Post $post): RedirectResponse
    {
        $this->postService->deletePost($post);

        return redirect()->route('posts.index')
            ->with('success', __('messages.success.post_deleted'));
    }

    public function adminDestroy(AdminDeletePostRequest $request, Post $post): RedirectResponse
    {
        $this->postService->deletePost($post);

        return redirect()->route('posts.index')
            ->with('success', __('messages.success.post_deleted_by_admin'));
    }
}
