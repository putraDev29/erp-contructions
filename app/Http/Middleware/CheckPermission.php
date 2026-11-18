<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (!$user || !$user->is_active) {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'Akun Anda tidak aktif atau sesi telah berakhir.']);
        }

        if (!$user->hasPermission($permission)) {
            abort(403, "Anda tidak memiliki izin akses [{$permission}] untuk tindakan ini.");
        }

        return $next($request);
    }
}
