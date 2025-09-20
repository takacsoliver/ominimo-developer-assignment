<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Data\Blog;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

final class PostData extends Data
{
    public function __construct(
        #[Required, StringType, Max(255)]
        public readonly string $title,

        #[Required, StringType, Min(10)]
        public readonly string $content,

        public readonly ?int $user_id = null,
    ) {}
}
