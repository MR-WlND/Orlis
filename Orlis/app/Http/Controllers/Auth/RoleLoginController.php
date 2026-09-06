<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
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
        $allRoles = array_merge(User::ROLES, Admin::ROLES);
        if (! array_key_exists($role, $allRoles)) {
            abort(404);
        }

        $isAdminRole = in_array($role, array_keys(Admin::ROLES));
        $guard = $isAdminRole ? 'admin' : 'web';

        if (Auth::guard($guard)->check()) {
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
            'roleLabel' => $allRoles[$role],
        ]);
    }

    public function login(Request $request, string $role): RedirectResponse
    {
        $allRoles = array_merge(User::ROLES, Admin::ROLES);
        if (! array_key_exists($role, $allRoles)) {
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

        // Xác định model và guard tương ứng với role
        $isAdminRole = in_array($role, array_keys(Admin::ROLES));
        $guard = $isAdminRole ? 'admin' : 'web';

        if ($isAdminRole) {
            $user = Admin::where('email', $request->email)->first();
        } else {
            $user = User::where('email', $request->email)->first();
        }

        if (! $user) {
            return back()
                ->withErrors(['email' => 'Tài khoản không tồn tại.'])
                ->onlyInput('email');
        }

        if ($user->role !== $role) {
            return back()
                ->withErrors(['email' => 'Tài khoản không có quyền truy cập.'])
                ->onlyInput('email');
        }

        if (! Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['password' => 'Mật khẩu không chính xác.'])
                ->onlyInput('email');
        }

        Auth::guard($guard)->login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        \Log::info('User logged in: '.$user->email.' with role '.$user->role.'. Guard: '.$guard.'. Redirecting to: '.$this->redirectTo($role));

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

    public function logout(Request $request): RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login/admin');
        }

        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login/customer');
        }

        return redirect('/');
    }
}
