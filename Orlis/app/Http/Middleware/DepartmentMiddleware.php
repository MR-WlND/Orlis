<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DepartmentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();

        if ($path === '/' || $path === 'home') {
            session(['department' => 'fashion']);
        } elseif ($path === 'beauty') {
            session(['department' => 'beauty']);
        } elseif (str_starts_with($path, 'catalog/')) {
            $slug = str_replace('catalog/', '', $path);
            if (str_contains($slug, 'nuoc-hoa') || str_contains($slug, 'lam-dep') || str_contains($slug, 'beauty')) {
                session(['department' => 'beauty']);
            } else {
                session(['department' => 'fashion']);
            }
        }
        
        // Default fallback if not set
        if (!session()->has('department')) {
            session(['department' => 'fashion']);
        }

        return $next($request);
    }
}
