<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace Tests\Unit\Policies;

use OminimoBlog\Models\Comment;
use OminimoBlog\Models\Post;
use OminimoBlog\Models\User;
use OminimoBlog\Policies\CommentPolicy;
use OminimoBlog\Enums\Role;
use OminimoBlog\Enums\Permission;
use Tests\TestCase;

class CommentPolicyTest extends TestCase
{

    private CommentPolicy $policy;
    private User $admin;
    private User $user;
    private Comment $comment;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->policy = new CommentPolicy();
        
        $this->admin = User::factory()->create();
        $this->admin->assignRole(Role::ADMIN->value);
        $this->admin->givePermissionTo(Permission::CREATE_COMMENTS->value);
        $this->admin->givePermissionTo(Permission::EDIT_ANY_COMMENT->value);
        $this->admin->givePermissionTo(Permission::DELETE_ANY_COMMENT->value);
        
        $this->user = User::factory()->create();
        $this->user->assignRole(Role::USER->value);
        $this->user->givePermissionTo(Permission::CREATE_COMMENTS->value);
        
        $post = Post::factory()->create(['user_id' => $this->user->id]);
        $this->comment = Comment::factory()->create([
            'user_id' => $this->user->id,
            'post_id' => $post->id
        ]);
    }

    public function testAdminCanCreateComments(): void
    {
        $this->assertTrue($this->policy->create($this->admin));
    }

    public function testUserCanCreateComments(): void
    {
        $this->assertTrue($this->policy->create($this->user));
    }

    public function testGuestCanCreateComments(): void
    {
        $this->assertTrue($this->policy->create(null));
    }

    public function testCommentOwnerCanUpdateComment(): void
    {
        $this->assertTrue($this->policy->update($this->user, $this->comment));
    }

    public function testAdminCanUpdateAnyComment(): void
    {
        $this->assertTrue($this->policy->update($this->admin, $this->comment));
    }

    public function testUserCannotUpdateOtherUsersComment(): void
    {
        $otherUser = User::factory()->create();
        $otherUser->assignRole(Role::USER->value);
        
        $this->assertFalse($this->policy->update($otherUser, $this->comment));
    }

    public function testGuestCannotUpdateComment(): void
    {
        $this->assertFalse($this->policy->update(null, $this->comment));
    }

    public function testCommentOwnerCanDeleteComment(): void
    {
        $this->assertTrue($this->policy->delete($this->user, $this->comment));
    }

    public function testAdminCanDeleteAnyComment(): void
    {
        $this->assertTrue($this->policy->delete($this->admin, $this->comment));
    }

    public function testUserCannotDeleteOtherUsersComment(): void
    {
        $otherUser = User::factory()->create();
        $otherUser->assignRole(Role::USER->value);
        
        $this->assertFalse($this->policy->delete($otherUser, $this->comment));
    }

    public function testGuestCannotDeleteComment(): void
    {
        $this->assertFalse($this->policy->delete(null, $this->comment));
    }

    public function testAdminCanDeleteAnyCommentWithDeleteAnyMethod(): void
    {
        $this->assertTrue($this->policy->deleteAny($this->admin));
    }

    public function testUserCannotDeleteAnyCommentWithDeleteAnyMethod(): void
    {
        $this->assertFalse($this->policy->deleteAny($this->user));
    }

    public function testGuestCannotDeleteAnyCommentWithDeleteAnyMethod(): void
    {
        $this->assertFalse($this->policy->deleteAny(null));
    }

    public function testUserWithEditAnyPermissionCanUpdateAnyComment(): void
    {
        $userWithEditAny = User::factory()->create();
        $userWithEditAny->assignRole(Role::USER->value);
        $userWithEditAny->givePermissionTo(Permission::EDIT_ANY_COMMENT->value);
        
        $this->assertTrue($this->policy->update($userWithEditAny, $this->comment));
    }

    public function testUserWithDeleteAnyPermissionCanDeleteAnyComment(): void
    {
        $userWithDeleteAny = User::factory()->create();
        $userWithDeleteAny->assignRole(Role::USER->value);
        $userWithDeleteAny->givePermissionTo(Permission::DELETE_ANY_COMMENT->value);
        
        $this->assertTrue($this->policy->delete($userWithDeleteAny, $this->comment));
    }

    public function testAdminCanApproveComments(): void
    {
        $this->assertTrue($this->policy->approve($this->admin, $this->comment));
    }

    public function testUserCannotApproveComments(): void
    {
        $this->assertFalse($this->policy->approve($this->user, $this->comment));
    }

    public function testGuestCannotApproveComments(): void
    {
        $this->assertFalse($this->policy->approve(null, $this->comment));
    }
}