<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Http\Controllers\Auth;

use OminimoBlog\Contracts\AuthServiceInterface;
use OminimoBlog\Data\Auth\ResetPasswordData;
use OminimoBlog\Http\Controllers\Controller;
use OminimoBlog\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

final class NewPasswordController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $authService
    ) {}

    public function create(Request $request): Response
    {
        return Inertia::render('Auth/ResetPassword', [
            'email' => $request->email,
            'token' => $request->route('token'),
        ]);
    }

    public function store(ResetPasswordRequest $request): RedirectResponse
    {
        $resetPasswordData = ResetPasswordData::from($request->validated());

        $status = $this->authService->resetPassword(
            $resetPasswordData,
            function ($user) use ($resetPasswordData) {
                $this->authService->resetUserPassword($user, $resetPasswordData->password);
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
