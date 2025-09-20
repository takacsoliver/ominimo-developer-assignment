<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Http\Resources\Blog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            'author' => [
                'id' => $this->user?->id,
                'name' => $this->author_name,
                'email' => $this->author_email,
            ],
            'post' => [
                'id' => $this->post->id,
                'title' => $this->post->title,
            ],
        ];
    }
}
