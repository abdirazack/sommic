<?php

namespace App\Http\Middleware;

use App\Constants\Status;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAccountOfficer {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $guard = 'branch_staff') {
        if (Auth::guard($guard)->user()->designation != Status::ROLE_CUSTOMER_SERVICE
        && Auth::guard($guard)->user()->designation != Status::ROLE_TELLER
        && Auth::guard($guard)->user()->designation != Status::ROLE_MANAGER
        && Auth::guard($guard)->user()->designation != Status::ROLE_ACCOUNTING
        
        ) {
            return to_route('staff.dashboard');
        }

        return $next($request);
    }
}
