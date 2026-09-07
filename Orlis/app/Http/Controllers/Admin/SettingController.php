<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Show the settings page.
     */
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            // Determine group based on key prefix
            $group = 'general';
            if (str_starts_with($key, 'vnpay_')) $group = 'payment';
            elseif (str_starts_with($key, 'mail_')) $group = 'mail';

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => $group]
            );
        }

        return redirect()->route('admin.settings.index')->with('success', 'Đã cập nhật cấu hình hệ thống.');
    }
}
