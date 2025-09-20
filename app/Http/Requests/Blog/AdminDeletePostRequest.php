<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;
use OminimoBlog\Models\Post;

final class AdminDeletePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('deleteAny', $this->route('post'));
    }

    public function rules(): array
    {
        return [];
    }
}
