<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsEditor
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isEditor()) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}