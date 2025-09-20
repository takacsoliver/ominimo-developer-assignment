<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

final class ProfileEditRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [];
    }
}
