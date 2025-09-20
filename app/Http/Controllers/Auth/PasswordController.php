<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Http\Controllers\Auth;

use OminimoBlog\Contracts\AuthServiceInterface;
use OminimoBlog\Data\Auth\UpdatePasswordData;
use OminimoBlog\Http\Controllers\Controller;
use OminimoBlog\Http\Requests\Auth\UpdatePasswordRequest;
use Illuminate\Http\RedirectResponse;

final class PasswordController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $authService
    ) {}

    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        $passwordData = UpdatePasswordData::from($request->validated());
        $this->authService->updateUserPassword($request->user(), $passwordData->password);

        return redirect()->route('profile.edit')->with('success', __('messages.success.password_changed'));
    }
}
