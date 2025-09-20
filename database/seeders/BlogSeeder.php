<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace Database\Seeders;

use OminimoBlog\Models\Comment;
use OminimoBlog\Models\Post;
use OminimoBlog\Models\User;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::role('admin')->first();
        $users = User::role('user')->get();

        Post::factory()
            ->count(15)
            ->create(['user_id' => $users->random()->id])
            ->each(function (Post $post) use ($users) {
                Comment::factory()
                    ->count(fake()->numberBetween(2, 8))
                    ->create([
                        'post_id' => $post->id,
                        'user_id' => $users->random()->id,
                    ]);
            });

        $welcomePost = Post::create([
            'title' => 'Welcome to Ominimo Blog',
            'slug' => 'welcome-to-ominimo-blog',
            'content' => '<p>Welcome to the Ominimo Blog! This is a developer interview assignment created for Ominimo, where I showcase my development skills and technical knowledge.</p><p>On this platform, I share my experiences with modern web development, the Laravel framework, Vue.js frontend technologies, and Docker containerization. The blog\'s purpose is to demonstrate my development workflow and the details of project implementation.</p><p>I hope that the content helps you understand my technical approach and code quality, which I bring to the Ominimo team during the selection process.</p>',
            'user_id' => $admin->id,
        ]);

        Comment::factory()
            ->count(5)
            ->create([
                'post_id' => $welcomePost->id,
                'user_id' => $users->random()->id,
            ]);
    }
}
