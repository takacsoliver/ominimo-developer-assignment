<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Data\Blog;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

final class CommentData extends Data
{
    public function __construct(
        #[Required, StringType, Max(1000)]
        public readonly string $comment,

        public readonly ?int $post_id = null,
        public readonly ?int $user_id = null,
        public readonly ?string $guest_name = null,
        public readonly ?string $guest_email = null,
    ) {}
}
