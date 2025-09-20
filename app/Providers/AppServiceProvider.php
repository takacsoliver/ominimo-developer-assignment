<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;
use OminimoBlog\Enums\Locale;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {
        JsonResource::withoutWrapping();

        Vite::prefetch(concurrency: 3);

        app()->setLocale(Locale::HUNGARIAN->value);
    }
}
