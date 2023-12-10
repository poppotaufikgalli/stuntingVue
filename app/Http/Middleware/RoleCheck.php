<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use Inertia\Inertia;
use Inertia\Response;

use App\Traits\HasRole;

class RoleCheck
{
    use HasRole;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!$this->allowed($request)){
            return redirect(RouteServiceProvider::HOME)->with([
                'type'      => 'info',
                'message'   => 'Akses anda dibatasi'
            ]);
        }

        return $next($request);
    }
}
