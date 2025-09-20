<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace Tests\Unit\Policies;

use OminimoBlog\Models\Post;
use OminimoBlog\Models\User;
use OminimoBlog\Policies\PostPolicy;
use OminimoBlog\Enums\Role;
use OminimoBlog\Enums\Permission;
use Tests\TestCase;

class PostPolicyTest extends TestCase
{

    private PostPolicy $policy;
    private User $admin;
    private User $user;
    private Post $post;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->policy = new PostPolicy();
        
        $this->admin = User::factory()->create();
        $this->admin->assignRole(Role::ADMIN->value);
        $this->admin->givePermissionTo(Permission::CREATE_POSTS->value);
        $this->admin->givePermissionTo(Permission::EDIT_ANY_POST->value);
        $this->admin->givePermissionTo(Permission::DELETE_ANY_POST->value);
        
        $this->user = User::factory()->create();
        $this->user->assignRole(Role::USER->value);
        $this->user->givePermissionTo(Permission::CREATE_POSTS->value);
        
        $this->post = Post::factory()->create(['user_id' => $this->user->id]);
    }

    public function testAdminCanCreatePosts(): void
    {
        $this->assertTrue($this->policy->create($this->admin));
    }

    public function testUserCanCreatePosts(): void
    {
        $this->assertTrue($this->policy->create($this->user));
    }

    public function testGuestCannotCreatePosts(): void
    {
        $this->assertFalse($this->policy->create(null));
    }

    public function testPostOwnerCanUpdatePost(): void
    {
        $this->assertTrue($this->policy->update($this->user, $this->post));
    }

    public function testAdminCanUpdateAnyPost(): void
    {
        $this->assertTrue($this->policy->update($this->admin, $this->post));
    }

    public function testUserCannotUpdateOtherUsersPost(): void
    {
        $otherUser = User::factory()->create();
        $otherUser->assignRole(Role::USER->value);
        
        $this->assertFalse($this->policy->update($otherUser, $this->post));
    }

    public function testGuestCannotUpdatePost(): void
    {
        $this->assertFalse($this->policy->update(null, $this->post));
    }

    public function testPostOwnerCanDeletePost(): void
    {
        $this->assertTrue($this->policy->delete($this->user, $this->post));
    }

    public function testAdminCanDeleteAnyPost(): void
    {
        $this->assertTrue($this->policy->delete($this->admin, $this->post));
    }

    public function testUserCannotDeleteOtherUsersPost(): void
    {
        $otherUser = User::factory()->create();
        $otherUser->assignRole(Role::USER->value);
        
        $this->assertFalse($this->policy->delete($otherUser, $this->post));
    }

    public function testGuestCannotDeletePost(): void
    {
        $this->assertFalse($this->policy->delete(null, $this->post));
    }

    public function testAdminCanDeleteAnyPostWithDeleteAnyMethod(): void
    {
        $this->assertTrue($this->policy->deleteAny($this->admin));
    }

    public function testUserCannotDeleteAnyPostWithDeleteAnyMethod(): void
    {
        $this->assertFalse($this->policy->deleteAny($this->user));
    }

    public function testGuestCannotDeleteAnyPostWithDeleteAnyMethod(): void
    {
        $this->assertFalse($this->policy->deleteAny(null));
    }

    public function testUserWithEditAnyPermissionCanUpdateAnyPost(): void
    {
        $userWithEditAny = User::factory()->create();
        $userWithEditAny->assignRole(Role::USER->value);
        $userWithEditAny->givePermissionTo(Permission::EDIT_ANY_POST->value);
        
        $this->assertTrue($this->policy->update($userWithEditAny, $this->post));
    }

    public function testUserWithDeleteAnyPermissionCanDeleteAnyPost(): void
    {
        $userWithDeleteAny = User::factory()->create();
        $userWithDeleteAny->assignRole(Role::USER->value);
        $userWithDeleteAny->givePermissionTo(Permission::DELETE_ANY_POST->value);
        
        $this->assertTrue($this->policy->delete($userWithDeleteAny, $this->post));
    }
}