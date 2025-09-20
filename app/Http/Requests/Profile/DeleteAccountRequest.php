<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

final class DeleteAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'password' => ['required', 'current_password'],
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => __('validation.required', ['attribute' => __('auth.password')]),
            'password.current_password' => __('validation.current_password'),
        ];
    }
}
