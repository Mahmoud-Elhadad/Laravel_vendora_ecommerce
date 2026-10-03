<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMerchentMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth("ecomm")->user()->merchent){
            if(auth('ecomm')->user()->merchent->status === "approved"){

                return $next($request);
            }
        }
        return redirect()->back()->with("error" , "Your account is waiting for approval.");
    }
}
