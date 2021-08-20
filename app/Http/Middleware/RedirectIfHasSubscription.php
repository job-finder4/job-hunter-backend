<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfHasSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if ($request->user()->hasActiveSubscription())
        {
            return redirect()
                ->route('home')
                ->withErrors(['you have another active subscription and you cant have more than one subscription']);
        }

        return $next($request);
    }
}
