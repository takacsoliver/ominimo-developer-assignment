<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Data\Auth;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Email;

final class ForgotPasswordData extends Data
{
    public function __construct(
        #[Required, Email]
        public readonly string $email,
    ) {}
}
