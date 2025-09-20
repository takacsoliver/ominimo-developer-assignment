<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

final class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
            'password_confirmation' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => __('validation.required', ['attribute' => __('profile.current_password')]),
            'current_password.current_password' => __('validation.current_password'),
            'password.required' => __('validation.required', ['attribute' => __('profile.new_password')]),
            'password.confirmed' => __('validation.confirmed', ['attribute' => __('profile.new_password')]),
        ];
    }
}
