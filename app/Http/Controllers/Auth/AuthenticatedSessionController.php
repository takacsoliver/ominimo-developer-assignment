<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Http\Controllers\Auth;

use OminimoBlog\Contracts\AuthServiceInterface;
use OminimoBlog\Http\Controllers\Controller;
use OminimoBlog\Http\Requests\Auth\LoginRequest;
use OminimoBlog\Http\Requests\Auth\LogoutRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

final class AuthenticatedSessionController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $authService
    ) {}

    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $this->authService->regenerateSession();

        return redirect()->intended(route('posts.index', absolute: false));
    }

    public function destroy(LogoutRequest $request = null): RedirectResponse
    {
        if (auth()->check()) {
            $this->authService->logoutUser();
            $this->authService->invalidateSession();
        }

        return redirect('/');
    }
}
