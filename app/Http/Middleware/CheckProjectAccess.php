<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProjectAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $projectId = $request->route('project') ?? $request->route('project_id') ?? $request->input('project_id');

        if ($projectId) {
            $id = is_object($projectId) ? $projectId->id : (int) $projectId;
            if (!$user->canAccessProject($id)) {
                abort(403, 'Anda tidak memiliki hak akses ke proyek ini.');
            }
        }

        return $next($request);
    }
}
