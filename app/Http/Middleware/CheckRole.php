<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->user();

        if ($role === 'admin' && !($user instanceof \App\Models\Admin)) {
            return response()->json(['success' => false, 'message' => 'مش مسموحلك'], 403);
        }

        if ($role !== 'admin' && $user->role !== $role) {
            return response()->json(['success' => false, 'message' => 'مش مسموحلك'], 403);
        }

        return $next($request);
    }
}
