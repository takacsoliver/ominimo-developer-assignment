<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use OminimoBlog\Enums\Role;

final class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->hasRole(Role::ADMIN->value)) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}
