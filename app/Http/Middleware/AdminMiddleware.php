<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //Auth::user()
        
        if ($request->user()->is_super_admin != '1')
        {
            return redirect('/dashboard');
        }

        return $next($request);
    }
}
