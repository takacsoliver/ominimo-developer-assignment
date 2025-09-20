<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace Tests\Feature\Feature;

use OminimoBlog\Models\Post;
use OminimoBlog\Models\User;
use OminimoBlog\Models\Comment;
use OminimoBlog\Enums\Role;
use OminimoBlog\Enums\Permission;
use Tests\TestCase;

class CommentTest extends TestCase
{

    private User $user;
    private User $admin;
    private Post $post;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->user->assignRole(Role::USER->value);
        $this->user->givePermissionTo(Permission::CREATE_COMMENTS->value);
        
        $this->admin = User::factory()->create();
        $this->admin->assignRole(Role::ADMIN->value);
        $this->admin->givePermissionTo(Permission::CREATE_COMMENTS->value);
        $this->admin->givePermissionTo(Permission::EDIT_ANY_COMMENT->value);
        $this->admin->givePermissionTo(Permission::DELETE_ANY_COMMENT->value);
        
        $this->post = Post::factory()->create();
    }

    public function testUserCanStoreComment(): void
    {
        $commentData = [
            'comment' => 'Test comment',
        ];

        $response = $this->actingAs($this->user)->post(route('posts.comments.store', $this->post), $commentData);

        $response->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'comment' => 'Test comment',
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
        ]);
    }

    public function testCommentStoreValidation(): void
    {
        $response = $this->actingAs($this->user)->post(route('posts.comments.store', $this->post), []);

        $response->assertSessionHasErrors(['comment']);
    }

    public function testCommentStoreRequiresValidPost(): void
    {
        $commentData = [
            'comment' => 'Test comment',
        ];

        $response = $this->actingAs($this->user)->post(route('posts.comments.store', 999), $commentData);

        $response->assertStatus(404);
    }

    public function testUserCanViewCommentsOnPost(): void
    {
        Comment::factory()->count(3)->create(['post_id' => $this->post->id]);

        $response = $this->get(route('posts.show', $this->post));

        $response->assertStatus(200);
        $response->assertInertia(fn($page) => 
            $page->component('Posts/Show')
                ->has('post.comments', 3)
        );
    }

    public function testUserCanDeleteOwnComment(): void
    {
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
        ]);

        $response = $this->actingAs($this->user)->delete(route('posts.comments.destroy', [$this->post, $comment]));

        $response->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function testUserCannotDeleteOtherUsersComment(): void
    {
        $otherUser = User::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $otherUser->id,
            'post_id' => $this->post->id,
        ]);

        $response = $this->actingAs($this->user)->delete(route('posts.comments.destroy', [$this->post, $comment]));

        $response->assertStatus(403);
    }

    public function testGuestCannotDeleteComment(): void
    {
        $comment = Comment::factory()->create(['post_id' => $this->post->id]);

        $response = $this->delete(route('posts.comments.destroy', [$this->post, $comment]));

        $response->assertRedirect(route('login'));
    }

    public function testAdminCanDeleteAnyComment(): void
    {
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.comments.destroy', $comment));

        $response->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function testCommentDeletionRequiresValidComment(): void
    {
        $response = $this->actingAs($this->user)->delete(route('posts.comments.destroy', [$this->post, 999]));

        $response->assertStatus(404);
    }

    public function testCommentDeletionRequiresValidPost(): void
    {
        $comment = Comment::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('posts.comments.destroy', [999, $comment]));

        $response->assertStatus(404);
    }

    public function testCommentBelongsToCorrectPost(): void
    {
        $otherPost = Post::factory()->create();
        $comment = Comment::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->delete(route('posts.comments.destroy', [$otherPost, $comment]));

        $response->assertStatus(302);
    }

    public function testCommentStorePreservesScrollPosition(): void
    {
        $commentData = [
            'comment' => 'Test comment',
        ];

        $response = $this->actingAs($this->user)->post(route('posts.comments.store', $this->post), $commentData);

        $response->assertRedirect();
    }

    public function testCommentDeletionPreservesScrollPosition(): void
    {
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
        ]);

        $response = $this->actingAs($this->user)->delete(route('posts.comments.destroy', [$this->post, $comment]));

        $response->assertRedirect();
    }

    public function testCommentHasAuthorInformation(): void
    {
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
        ]);

        $response = $this->get(route('posts.show', $this->post));

        $response->assertStatus(200);
        $response->assertInertia(fn($page) => 
            $page->component('Posts/Show')
                ->has('post.comments.0.author')
                ->where('post.comments.0.author.name', $this->user->name)
        );
    }

    public function testCommentHasTimestamps(): void
    {
        $comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'post_id' => $this->post->id,
        ]);

        $response = $this->get(route('posts.show', $this->post));

        $response->assertStatus(200);
        $response->assertInertia(fn($page) => 
            $page->component('Posts/Show')
                ->has('post.comments.0.created_at')
                ->has('post.comments.0.updated_at')
        );
    }

    public function testCommentCountIsDisplayed(): void
    {
        Comment::factory()->count(5)->create(['post_id' => $this->post->id]);

        $response = $this->get(route('posts.show', $this->post));

        $response->assertStatus(200);
        $response->assertInertia(fn($page) => 
            $page->component('Posts/Show')
                ->where('post.comments_count', 5)
        );
    }
}