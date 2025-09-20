<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Data\Profile;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\CurrentPassword;

final class DeleteAccountData extends Data
{
    public function __construct(
        #[Required, StringType, CurrentPassword]
        public readonly string $password,
    ) {}
}
