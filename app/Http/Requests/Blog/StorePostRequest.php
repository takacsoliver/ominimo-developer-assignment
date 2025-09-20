<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OminimoBlog\Models\Post;

final class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Post::class);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:10'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => __('validation.required', ['attribute' => __('attributes.title')]),
            'title.max' => __('validation.max.string', ['attribute' => __('attributes.title'), 'max' => 255]),
            'content.required' => __('validation.required', ['attribute' => __('attributes.content')]),
            'content.min' => __('validation.min.string', ['attribute' => __('attributes.content'), 'min' => 10]),
        ];
    }
}
