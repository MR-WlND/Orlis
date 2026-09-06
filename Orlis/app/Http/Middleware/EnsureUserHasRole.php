<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $admin = Auth::guard('admin')->user();
        $web = Auth::guard('web')->user();

        $user = null;
        if ($admin && $admin->hasAnyRole($roles)) {
            $user = $admin;
        } elseif ($web && $web->hasAnyRole($roles)) {
            $user = $web;
        }

        if (! $user) {
            abort(403, 'Bạn không có quyền truy cập chức năng này.');
        }

        // Cập nhật lại user vào request để dùng chung nếu cần
        $request->setUserResolver(fn () => $user);

        return $next($request);
    }
}
