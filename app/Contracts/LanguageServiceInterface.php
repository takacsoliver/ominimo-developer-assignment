<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Contracts;

use Illuminate\Http\RedirectResponse;

interface LanguageServiceInterface
{
    public function switchLanguage(string $locale): RedirectResponse;
}
