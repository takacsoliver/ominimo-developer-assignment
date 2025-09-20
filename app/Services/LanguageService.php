<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Services;

use Illuminate\Http\RedirectResponse;
use OminimoBlog\Contracts\LanguageServiceInterface;
use OminimoBlog\Enums\Locale;

class LanguageService implements LanguageServiceInterface
{
    public function switchLanguage(string $locale): RedirectResponse
    {
        if (!Locale::isValid($locale)) {
            return redirect()->back();
        }

        $this->setLocale($locale);
        $this->persistLocale($locale);

        return redirect()->back()->withCookie(
            cookie('locale', $locale, 60 * 24 * 30)
        );
    }

    private function setLocale(string $locale): void
    {
        app()->setLocale($locale);
    }

    private function persistLocale(string $locale): void
    {
        session(['locale' => $locale]);
    }
}
