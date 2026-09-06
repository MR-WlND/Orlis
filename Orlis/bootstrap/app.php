<?php

use App\Http\Middleware\DepartmentMiddleware;
use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Middleware\LanguageMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            DepartmentMiddleware::class,
            LanguageMiddleware::class,
        ]);
        $middleware->alias([
            'role' => EnsureUserHasRole::class,
        ]);
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('admin*') || $request->is('shipper*') || $request->is('staff*') || $request->is('manager*') || $request->is('warehouse*') || $request->is('supplier*')) {
                $role = explode('/', $request->path())[0];
                if ($role === 'admin') {
                    $role = 'admin';
                }

                return route('role.login', ['role' => $role]);
            }

            return route('role.login', ['role' => 'customer']);
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
