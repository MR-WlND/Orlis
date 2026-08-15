<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = \Illuminate\Support\Facades\Auth::guard('admin')->user() ?? \Illuminate\Support\Facades\Auth::guard('web')->user();

        if (! $user || ! $user->hasAnyRole($roles)) {
            abort(403, 'Bạn không có quyền truy cập chức năng này.');
        }

        // Cập nhật lại user vào request để dùng chung nếu cần
        $request->setUserResolver(fn() => $user);

        return $next($request);
    }
}
