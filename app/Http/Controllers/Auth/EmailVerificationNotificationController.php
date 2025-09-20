<?php
/*
 * Ominimo Blog - Developer interview assignment
 */

declare(strict_types=1);

namespace OminimoBlog\Http\Controllers\Auth;

use OminimoBlog\Contracts\AuthServiceInterface;
use OminimoBlog\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class EmailVerificationNotificationController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $authService
    ) {}

    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('posts.index', absolute: false));
        }

        $this->authService->sendEmailVerification($request->user());

        return back()->with('status', 'verification-link-sent');
    }
}
