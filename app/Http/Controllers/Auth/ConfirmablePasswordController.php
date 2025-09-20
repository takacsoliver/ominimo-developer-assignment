<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Http\Controllers\Auth;

use OminimoBlog\Contracts\AuthServiceInterface;
use OminimoBlog\Data\Auth\ConfirmPasswordData;
use OminimoBlog\Http\Controllers\Controller;
use OminimoBlog\Http\Requests\Auth\ConfirmPasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

final class ConfirmablePasswordController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $authService
    ) {}

    public function show(): Response
    {
        return Inertia::render('Auth/ConfirmPassword');
    }

    public function store(ConfirmPasswordRequest $request): RedirectResponse
    {
        $passwordData = ConfirmPasswordData::from($request->validated());

        if (!$this->authService->confirmPassword($request->user(), $passwordData->password)) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $this->authService->setPasswordConfirmed();

        return redirect()->intended(route('posts.index', absolute: false));
    }
}
