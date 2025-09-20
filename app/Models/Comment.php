<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment',
        'post_id',
        'user_id',
        'guest_name',
        'guest_email',
    ];

    protected $appends = [
        'author_name',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getAuthorNameAttribute(): string
    {
        return $this->user ? $this->user->name : $this->guest_name;
    }

    public function getAuthorEmailAttribute(): string
    {
        return $this->user ? $this->user->email : $this->guest_email;
    }
}
