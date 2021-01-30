<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EmployeeGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->session()->has('employee_id')) {
            return redirect(route('dashboard_v2'));
        }

        return $next($request);
    }
}
