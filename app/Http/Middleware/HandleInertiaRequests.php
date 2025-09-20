<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use OminimoBlog\Enums\Locale;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $locale = session('locale') ?? $request->cookie('locale', config('app.locale'));
        
        if (Locale::isValid($locale)) {
            app()->setLocale($locale);
        }
        
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user() ? $request->user()->load('roles') : null,
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'locale' => app()->getLocale(),
            'fallbackLocale' => Locale::ENGLISH->value,
            'availableLocales' => array_map(
                fn(Locale $locale) => [
                    'code' => $locale->value,
                    'name' => $locale->getDisplayName(),
                ],
                Locale::cases()
            ),
        ];
    }
}
