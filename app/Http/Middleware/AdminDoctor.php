<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class AdminDoctor
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
        if(Auth::check()) {
            if (Auth::user()->role == 'admin' || Auth::user()->role == 'doctor' || $request->ajax()){ 
                return $next($request);
            } else {
                return redirect()->route('appointments.index')
                        ->with('error','Access denied.');
            }
        }
    }
}
