<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsEnterpriseSelected
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('actualEnterpriseId')) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
