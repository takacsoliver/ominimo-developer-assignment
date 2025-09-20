<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Services;

use OminimoBlog\Contracts\AuthServiceInterface;
use OminimoBlog\Data\Auth\ForgotPasswordData;
use OminimoBlog\Data\Auth\RegisterData;
use OminimoBlog\Data\Auth\ResetPasswordData;
use OminimoBlog\Data\Profile\DeleteAccountData;
use OminimoBlog\Data\Profile\UpdateProfileData;
use OminimoBlog\Models\User;
use OminimoBlog\Enums\Role;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

final class AuthService implements AuthServiceInterface
{
    public function registerUser(RegisterData $data): User
    {
        $user = User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);

        $user->assignRole(Role::USER->value);

        event(new Registered($user));

        return $user;
    }

    public function loginUser(User $user): void
    {
        Auth::login($user);
    }

    public function logoutUser(): void
    {
        Auth::guard('web')->logout();
    }

    public function updateUserPassword(User $user, string $newPassword): User
    {
        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        return $user->fresh();
    }

    public function regenerateSession(): void
    {
        if (request()->hasSession()) {
            request()->session()->regenerate();
        }
    }

    public function invalidateSession(): void
    {
        if (request()->hasSession()) {
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }
    }

    public function sendPasswordResetLink(ForgotPasswordData $data): string
    {
        return Password::sendResetLink(['email' => $data->email]);
    }

    public function resetPassword(ResetPasswordData $data, callable $callback): string
    {
        return Password::reset($data->toArray(), $callback);
    }

    public function resetUserPassword(User $user, string $password): User
    {
        $user->forceFill([
            'password' => Hash::make($password),
            'remember_token' => Str::random(60),
        ])->save();

        event(new PasswordReset($user));

        return $user->fresh();
    }

    public function updateUserProfile(User $user, UpdateProfileData $data): User
    {
        $user->fill($data->toArray());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return $user->fresh();
    }

    public function deleteUserAccount(User $user, DeleteAccountData $deleteData): void
    {
        Auth::logout();
        $user->delete();
        $this->invalidateSession();
    }

    public function sendEmailVerification(User $user): void
    {
        if (!$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }
    }

    public function confirmPassword(User $user, string $password): bool
    {
        return Auth::guard('web')->validate([
            'email' => $user->email,
            'password' => $password,
        ]);
    }

    public function setPasswordConfirmed(): void
    {
        if (request()->hasSession()) {
            request()->session()->put('auth.password_confirmed_at', time());
        }
    }
}
