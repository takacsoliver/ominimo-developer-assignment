<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Http\Controllers;

use OminimoBlog\Contracts\AuthServiceInterface;
use OminimoBlog\Data\Profile\DeleteAccountData;
use OminimoBlog\Data\Profile\UpdateProfileData;
use OminimoBlog\Http\Requests\Profile\DeleteAccountRequest;
use OminimoBlog\Http\Requests\Profile\ProfileEditRequest;
use OminimoBlog\Http\Requests\Profile\ProfileUpdateRequest;
use OminimoBlog\Http\Resources\UserResource;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class ProfileController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $authService
    ) {}

    public function edit(ProfileEditRequest $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'user' => UserResource::make($request->user()),
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $profileData = UpdateProfileData::from($request->validated());
        $this->authService->updateUserProfile($request->user(), $profileData);

        return redirect()->route('profile.edit')
            ->with('success', __('messages.success.profile_updated'));
    }

    public function destroy(DeleteAccountRequest $request): RedirectResponse
    {
        $deleteData = DeleteAccountData::from($request->validated());
        $this->authService->deleteUserAccount($request->user(), $deleteData);

        return redirect('/')
            ->with('success', __('messages.success.account_deleted'));
    }
}
