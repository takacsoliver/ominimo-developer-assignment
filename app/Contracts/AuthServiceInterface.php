<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Contracts;

use OminimoBlog\Data\Auth\ForgotPasswordData;
use OminimoBlog\Data\Auth\RegisterData;
use OminimoBlog\Data\Auth\ResetPasswordData;
use OminimoBlog\Data\Profile\DeleteAccountData;
use OminimoBlog\Data\Profile\UpdateProfileData;
use OminimoBlog\Models\User;

interface AuthServiceInterface
{
    public function registerUser(RegisterData $data): User;

    public function loginUser(User $user): void;

    public function logoutUser(): void;

    public function updateUserPassword(User $user, string $newPassword): User;

    public function sendPasswordResetLink(ForgotPasswordData $data): string;

    public function resetPassword(ResetPasswordData $data, callable $callback): string;

    public function resetUserPassword(User $user, string $password): User;

    public function regenerateSession(): void;

    public function invalidateSession(): void;

    public function updateUserProfile(User $user, UpdateProfileData $data): User;

    public function deleteUserAccount(User $user, DeleteAccountData $deleteData): void;

    public function sendEmailVerification(User $user): void;

    public function confirmPassword(User $user, string $password): bool;

    public function setPasswordConfirmed(): void;
}
