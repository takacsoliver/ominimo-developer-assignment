<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace Database\Factories;

use OminimoBlog\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->sentence(fake()->numberBetween(3, 8), false);
        
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => fake()->randomHtml(3, 5),
            'user_id' => User::factory(),
        ];
    }
}