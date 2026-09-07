<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'phone'   => 'nullable|string|max:20',
            'subject' => 'required|string|max:100',
            'message' => 'required|string|min:10',
        ], [
            'name.required'    => 'Vui lòng nhập họ tên.',
            'email.required'   => 'Vui lòng nhập địa chỉ email.',
            'email.email'      => 'Email không hợp lệ.',
            'subject.required' => 'Vui lòng chọn chủ đề.',
            'message.required' => 'Vui lòng nhập nội dung.',
            'message.min'      => 'Nội dung tối thiểu 10 ký tự.',
        ]);

        // Log the contact request (can be replaced with actual email sending)
        Log::info('Contact form submitted', [
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        // Optionally send email to admin
        // Mail::to(config('mail.from.address'))->send(new \App\Mail\ContactFormMail($request->all()));

        return back()->with('contact_success', true);
    }
}
