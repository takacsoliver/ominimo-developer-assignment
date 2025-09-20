<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Http\Controllers\Auth;

use OminimoBlog\Contracts\AuthServiceInterface;
use OminimoBlog\Data\Auth\RegisterData;
use OminimoBlog\Http\Controllers\Controller;
use OminimoBlog\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class RegisteredUserController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $authService
    ) {}

    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $registerData = RegisterData::from($request->validated());
        $user = $this->authService->registerUser($registerData);
        $this->authService->loginUser($user);

        return redirect(route('posts.index', absolute: false));
    }
}
