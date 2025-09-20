<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Providers;

use OminimoBlog\Contracts\AuthServiceInterface;
use OminimoBlog\Contracts\CommentServiceInterface;
use OminimoBlog\Contracts\LanguageServiceInterface;
use OminimoBlog\Contracts\PostServiceInterface;
use OminimoBlog\Services\AuthService;
use OminimoBlog\Services\CommentService;
use OminimoBlog\Services\LanguageService;
use OminimoBlog\Services\PostService;
use Illuminate\Support\ServiceProvider;

final class ServiceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PostServiceInterface::class, PostService::class);
        $this->app->bind(CommentServiceInterface::class, CommentService::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(LanguageServiceInterface::class, LanguageService::class);
    }

    public function boot(): void
    {
        //
    }
}
