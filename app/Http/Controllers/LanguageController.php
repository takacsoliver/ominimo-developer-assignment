<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use OminimoBlog\Contracts\LanguageServiceInterface;

class LanguageController extends Controller
{
    public function __construct(
        private readonly LanguageServiceInterface $languageService
    ) {}

    public function switch(string $locale): RedirectResponse
    {
        return $this->languageService->switchLanguage($locale);
    }
}
