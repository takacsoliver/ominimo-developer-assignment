<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Enums;

enum Locale: string
{
    case HUNGARIAN = 'hu';
    case ENGLISH = 'en';

    public static function isValid(string $locale): bool
    {
        return in_array($locale, self::values(), true);
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getDisplayName(): string
    {
        return match ($this) {
            self::HUNGARIAN => 'Magyar',
            self::ENGLISH => 'English',
        };
    }
}
