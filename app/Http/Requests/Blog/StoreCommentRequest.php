<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;
use OminimoBlog\Models\Comment;

final class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'comment' => ['required', 'string', 'max:1000'],
        ];

        if (!$this->user()) {
            $rules['guest_name'] = ['required', 'string', 'max:255'];
            $rules['guest_email'] = ['required', 'email', 'max:255'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'comment.required' => __('validation.required', ['attribute' => __('attributes.comment')]),
            'comment.max' => __('validation.max.string', ['attribute' => __('attributes.comment'), 'max' => 1000]),
            'guest_name.required' => __('validation.required', ['attribute' => __('attributes.guest_name')]),
            'guest_name.max' => __('validation.max.string', ['attribute' => __('attributes.guest_name'), 'max' => 255]),
            'guest_email.required' => __('validation.required', ['attribute' => __('attributes.guest_email')]),
            'guest_email.email' => __('validation.email', ['attribute' => __('attributes.guest_email')]),
            'guest_email.max' => __('validation.max.string', ['attribute' => __('attributes.guest_email'), 'max' => 255]),
        ];
    }
}
