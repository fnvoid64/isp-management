<?php

namespace App\Http\Middleware;

use App\Models\Employee;
use Closure;
use Illuminate\Http\Request;

class EmployeeAuth
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
        if (!$request->session()->has('employee_id')) {
            return redirect(route('employee_login'));
        }

        Employee::setEmployee($request->session()->get('employee_id'));

        return $next($request);
    }
}
