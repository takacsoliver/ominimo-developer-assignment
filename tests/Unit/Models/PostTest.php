<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace Tests\Unit\Models;

use OminimoBlog\Models\Post;
use OminimoBlog\Models\User;
use OminimoBlog\Models\Comment;
use Tests\TestCase;

class PostTest extends TestCase
{

    public function testPostBelongsToUser(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $post->user);
        $this->assertEquals($user->id, $post->user->id);
    }

    public function testPostHasManyComments(): void
    {
        $post = Post::factory()->create();
        $comment1 = Comment::factory()->create(['post_id' => $post->id]);
        $comment2 = Comment::factory()->create(['post_id' => $post->id]);

        $this->assertCount(2, $post->comments);
        $this->assertTrue($post->comments->contains($comment1));
        $this->assertTrue($post->comments->contains($comment2));
    }

    public function testPostHasAuthorNameAttribute(): void
    {
        $user = User::factory()->create(['name' => 'John Doe']);
        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->assertEquals('John Doe', $post->author_name);
    }
}