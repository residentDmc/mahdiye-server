<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role == "admin") {
            return $next($request);
        }
        abort(403, "کاربر گرامی شما مجوز لازم برای مشاهده و بررسی این بخش از سامانه را ندارید. در صورت نیاز می توانید با مدیریت در ارتباط باشید.");
    }
}
