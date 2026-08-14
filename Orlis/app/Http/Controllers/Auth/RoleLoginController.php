<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RoleLoginController extends Controller
{
    public function showLoginForm(string $role): View|RedirectResponse
    {
        if (! array_key_exists($role, User::ROLES)) {
            abort(404);
        }

        if (Auth::check()) {
            return redirect($this->redirectTo($role));
        }

        $viewName = "auth.logins.{$role}";
        if (view()->exists($viewName)) {
            return view($viewName, [
                'role' => $role,
                'roleLabel' => User::ROLES[$role],
            ]);
        }

        return view('auth.role-login', [
            'role' => $role,
            'roleLabel' => User::ROLES[$role],
        ]);
    }

    public function login(Request $request, string $role): RedirectResponse
    {
        if (! array_key_exists($role, User::ROLES)) {
            abort(404);
        }

        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || $user->role !== $role || ! Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors([
                    'email' => 'Thông tin đăng nhập không đúng hoặc vai trò không phù hợp.',
                ])
                ->onlyInput('email');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended($this->redirectTo($role));
    }

    protected function redirectTo(string $role): string
    {
        return match ($role) {
            'admin' => '/admin',
            'manager' => '/manager',
            'staff' => '/staff',
            'customer' => '/',
            'shipper' => '/shipper',
            'warehouse_staff' => '/warehouse',
            'supplier' => '/supplier',
            'guest' => '/guest',
            default => '/',
        };
    }
}
