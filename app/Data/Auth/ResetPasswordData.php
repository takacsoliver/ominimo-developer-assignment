<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Data\Auth;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Email;

final class ResetPasswordData extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string $token,

        #[Required, Email]
        public readonly string $email,

        #[Required, StringType, Min(8)]
        public readonly string $password,

        #[Required, StringType]
        public readonly string $password_confirmation,
    ) {}
}
