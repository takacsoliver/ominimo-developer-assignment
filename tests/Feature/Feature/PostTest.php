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

class PostTest extends TestCase
{

    private User $user;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->user->assignRole(Role::USER->value);
        $this->user->givePermissionTo(Permission::CREATE_POSTS->value);
        
        $this->admin = User::factory()->create();
        $this->admin->assignRole(Role::ADMIN->value);
        $this->admin->givePermissionTo(Permission::CREATE_POSTS->value);
        $this->admin->givePermissionTo(Permission::EDIT_ANY_POST->value);
        $this->admin->givePermissionTo(Permission::DELETE_ANY_POST->value);
    }

    public function testGuestCanViewPostsIndex(): void
    {
        Post::factory()->count(3)->create();

        $response = $this->get(route('posts.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn($page) => 
            $page->component('Posts/Index')
                ->has('posts', 3)
                ->has('pagination')
        );
    }

    public function testGuestCanViewPostShow(): void
    {
        $post = Post::factory()->create();

        $response = $this->get(route('posts.show', $post));

        $response->assertStatus(200);
        $response->assertInertia(fn($page) => 
            $page->component('Posts/Show')
                ->has('post')
                ->where('post.id', $post->id)
        );
    }

    public function testGuestCannotViewPostCreate(): void
    {
        $response = $this->get(route('posts.create'));

        $response->assertRedirect(route('login'));
    }

    public function testGuestCannotStorePost(): void
    {
        $postData = [
            'title' => 'Test Post',
            'content' => 'Test content',
        ];

        $response = $this->post(route('posts.store'), $postData);

        $response->assertRedirect(route('login'));
    }

    public function testUserCanViewPostCreate(): void
    {
        $response = $this->actingAs($this->user)->get(route('posts.create'));

        $response->assertStatus(200);
        $response->assertInertia(fn($page) => 
            $page->component('Posts/Create')
        );
    }

    public function testUserCanStorePost(): void
    {
        $postData = [
            'title' => 'Test Post',
            'content' => 'Test content',
        ];

        $response = $this->actingAs($this->user)->post(route('posts.store'), $postData);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'content' => 'Test content',
            'user_id' => $this->user->id,
        ]);
    }

    public function testUserCanViewOwnPostEdit(): void
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('posts.edit', $post));

        $response->assertStatus(200);
        $response->assertInertia(fn($page) => 
            $page->component('Posts/Edit')
                ->has('post')
                ->where('post.id', $post->id)
        );
    }

    public function testUserCannotViewOtherUsersPostEdit(): void
    {
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)->get(route('posts.edit', $post));

        $response->assertStatus(200);
    }

    public function testUserCanUpdateOwnPost(): void
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);
        $updateData = [
            'title' => 'Updated Post',
            'content' => 'Updated content',
        ];

        $response = $this->actingAs($this->user)->put(route('posts.update', $post), $updateData);

        $response->assertRedirect(route('posts.show', $post));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Post',
            'content' => 'Updated content',
        ]);
    }

    public function testUserCannotUpdateOtherUsersPost(): void
    {
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $otherUser->id]);
        $updateData = [
            'title' => 'Updated Post',
            'content' => 'Updated content',
        ];

        $response = $this->actingAs($this->user)->put(route('posts.update', $post), $updateData);

        $response->assertStatus(403);
    }

    public function testUserCanDeleteOwnPost(): void
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->delete(route('posts.destroy', $post));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function testUserCannotDeleteOtherUsersPost(): void
    {
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)->delete(route('posts.destroy', $post));

        $response->assertStatus(403);
    }

    public function testAdminCanViewAnyPostEdit(): void
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('posts.edit', $post));

        $response->assertStatus(200);
        $response->assertInertia(fn($page) => 
            $page->component('Posts/Edit')
                ->has('post')
                ->where('post.id', $post->id)
        );
    }

    public function testAdminCanUpdateAnyPost(): void
    {
        $post = Post::factory()->create();
        $updateData = [
            'title' => 'Updated Post',
            'content' => 'Updated content',
        ];

        $response = $this->actingAs($this->admin)->put(route('posts.update', $post), $updateData);

        $response->assertRedirect(route('posts.show', $post));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Post',
            'content' => 'Updated content',
        ]);
    }

    public function testAdminCanDeleteAnyPost(): void
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.posts.destroy', $post));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function testPostStoreValidation(): void
    {
        $response = $this->actingAs($this->user)->post(route('posts.store'), []);

        $response->assertSessionHasErrors(['title', 'content']);
    }

    public function testPostUpdateValidation(): void
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->put(route('posts.update', $post), []);

        $response->assertSessionHasErrors(['title', 'content']);
    }

    public function testPostShowDisplaysComments(): void
    {
        $post = Post::factory()->create();
        Comment::factory()->count(3)->create(['post_id' => $post->id]);

        $response = $this->get(route('posts.show', $post));

        $response->assertStatus(200);
        $response->assertInertia(fn($page) => 
            $page->component('Posts/Show')
                ->has('post.comments', 3)
        );
    }

    public function testPostIndexPagination(): void
    {
        Post::factory()->count(15)->create();

        $response = $this->get(route('posts.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn($page) => 
            $page->component('Posts/Index')
                ->has('pagination')
                ->where('pagination.current_page', 1)
                ->where('pagination.last_page', 2)
        );
    }
}