<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace Tests\Unit\Models;

use OminimoBlog\Models\Comment;
use OminimoBlog\Models\Post;
use OminimoBlog\Models\User;
use Tests\TestCase;

class CommentTest extends TestCase
{

    public function testCommentBelongsToUser(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $comment->user);
        $this->assertEquals($user->id, $comment->user->id);
    }

    public function testCommentBelongsToPost(): void
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->create(['post_id' => $post->id]);

        $this->assertInstanceOf(Post::class, $comment->post);
        $this->assertEquals($post->id, $comment->post->id);
    }

    public function testCommentHasAuthorNameAttribute(): void
    {
        $user = User::factory()->create(['name' => 'Jane Doe']);
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $this->assertEquals('Jane Doe', $comment->author_name);
    }

    public function testCommentIsFillable(): void
    {
        $comment = new Comment();
        $fillable = $comment->getFillable();

        $this->assertContains('comment', $fillable);
        $this->assertContains('post_id', $fillable);
        $this->assertContains('user_id', $fillable);
    }

    public function testCommentHasAppends(): void
    {
        $comment = Comment::factory()->create();
        $appends = $comment->getAppends();

        $this->assertContains('author_name', $appends);
    }

    public function testCommentCanBeCreatedWithValidData(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        
        $comment = Comment::create([
            'comment' => 'This is a test comment',
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('comments', [
            'comment' => 'This is a test comment',
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        $this->assertEquals('This is a test comment', $comment->comment);
        $this->assertEquals($post->id, $comment->post_id);
        $this->assertEquals($user->id, $comment->user_id);
    }

    public function testCommentCanBeUpdated(): void
    {
        $comment = Comment::factory()->create(['comment' => 'Original comment']);
        
        $comment->update(['comment' => 'Updated comment']);

        $this->assertEquals('Updated comment', $comment->fresh()->comment);
    }

    public function testCommentCanBeDeleted(): void
    {
        $comment = Comment::factory()->create();

        $comment->delete();

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function testCommentHasTimestamps(): void
    {
        $comment = Comment::factory()->create();

        $this->assertNotNull($comment->created_at);
        $this->assertNotNull($comment->updated_at);
    }

    public function testCommentFactoryCreatesValidComment(): void
    {
        $comment = Comment::factory()->create();

        $this->assertInstanceOf(Comment::class, $comment);
        $this->assertIsString($comment->comment);
        $this->assertNotNull($comment->post_id);
        $this->assertNotNull($comment->user_id);
    }
}