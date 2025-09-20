<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

final class ConfirmPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'password' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => __('validation.required', ['attribute' => __('auth.password')]),
        ];
    }
}
