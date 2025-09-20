<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace Database\Factories;

use OminimoBlog\Models\Post;
use OminimoBlog\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'comment' => fake()->paragraphs(fake()->numberBetween(1, 3), true),
            'user_id' => User::factory(),
            'post_id' => Post::factory(),
        ];
    }
}