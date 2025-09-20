<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;
use OminimoBlog\Models\Comment;

final class AdminDeleteCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('deleteAny', $this->route('comment'));
    }

    public function rules(): array
    {
        return [];
    }
}
