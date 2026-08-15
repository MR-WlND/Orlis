<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminAccountController extends Controller
{
    public const ROLES = [
        'admin' => 'Quản trị viên',
        'manager' => 'Quản lý',
        'staff' => 'Nhân viên',
        'editor' => 'Biên tập viên',
        'warehouse_staff' => 'Quản lý kho',
    ];

    public function index(Request $request)
    {
        $query = Admin::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $admins = $query->latest()->paginate(10)->withQueryString();
        
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        $roles = self::ROLES;
        return view('admin.admins.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'phone' => ['nullable', 'string', 'max:20', 'unique:admins'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(array_keys(self::ROLES))],
            'status' => ['required', 'in:0,1,2'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        Admin::create($validated);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Tạo tài khoản quản trị thành công!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Admin $admin)
    {
        $roles = self::ROLES;
        return view('admin.admins.edit', compact('admin', 'roles'));
    }

    public function update(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admins')->ignore($admin->id)],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('admins')->ignore($admin->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', Rule::in(array_keys(self::ROLES))],
            'status' => ['required', 'in:0,1,2'],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $admin->update($validated);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Cập nhật tài khoản quản trị thành công!');
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', 'Xóa tài khoản quản trị thành công!');
    }
}
