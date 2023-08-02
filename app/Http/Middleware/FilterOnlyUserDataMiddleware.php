<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FilterOnlyUserDataMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */


    /*** This middleware will be applied on the 'index' of controllers. It will be a parameter that tells the controller
     * to only return the data of the logged-in user, not the entire staff.
     * Why doing it this way? Because I don't want to create a separate controller method to retrieve current user data
     * and repeat the exact same code,
     * This middleware will send a parameter to the controller, if it is true, then the index will add a where clause
     * that filters only the logged-in user data.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->merge(['FilterMyData' => true]);
        return $next($request);
    }
}
