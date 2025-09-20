<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

final class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }

    public function messages(): array
    {
        return [
            'token.required' => __('validation.required', ['attribute' => __('auth.token')]),
            'email.required' => __('validation.required', ['attribute' => __('auth.email')]),
            'email.email' => __('validation.email', ['attribute' => __('auth.email')]),
            'password.required' => __('validation.required', ['attribute' => __('auth.password')]),
            'password.confirmed' => __('validation.confirmed', ['attribute' => __('auth.password')]),
        ];
    }
}
