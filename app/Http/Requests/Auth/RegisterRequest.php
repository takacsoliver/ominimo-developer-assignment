<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Http\Requests\Auth;

use OminimoBlog\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

final class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('auth.name')]),
            'name.max' => __('validation.max.string', ['attribute' => __('auth.name'), 'max' => 255]),
            'email.required' => __('validation.required', ['attribute' => __('auth.email')]),
            'email.email' => __('validation.email', ['attribute' => __('auth.email')]),
            'email.unique' => __('validation.unique', ['attribute' => __('auth.email')]),
            'password.required' => __('validation.required', ['attribute' => __('auth.password')]),
            'password.confirmed' => __('validation.confirmed', ['attribute' => __('auth.password')]),
        ];
    }
}
