<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Data\Auth;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;

final class ConfirmPasswordData extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string $password,
    ) {}
}
