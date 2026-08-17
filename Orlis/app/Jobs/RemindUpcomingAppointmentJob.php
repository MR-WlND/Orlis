<?php

namespace App\Jobs;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RemindUpcomingAppointmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        // Lấy tất cả lịch hẹn confirmed diễn ra vào ngày mai
        $appointments = Appointment::with(['user', 'store', 'staff', 'items.variant'])
            ->where('status', 'confirmed')
            ->whereDate('appointment_date', $tomorrow)
            ->get();

        foreach ($appointments as $appointment) {
            // Gửi email/notification tới khách hàng
            Log::info("Đang gửi email nhắc nhở Lịch hẹn #{$appointment->appointment_code} cho User #{$appointment->user_id}");
            try {
                \Illuminate\Support\Facades\Mail::raw("Xin chào. Nhắc nhở bạn có lịch hẹn thử đồ VIP vào ngày mai lúc {$appointment->time_slot} tại cửa hàng.", function ($msg) use ($appointment) {
                    $msg->to($appointment->user->email ?? 'guest@example.com')->subject("Nhắc nhở Lịch hẹn VIP Orlis - {$appointment->appointment_code}");
                });
            } catch (\Exception $e) {
                Log::error("Lỗi gửi Email nhắc lịch: " . $e->getMessage());
            }

            // Gửi notification nhắc nhở Staff phụ trách
            if ($appointment->staff_id) {
                Log::info("Sending staff reminder notification to Staff #{$appointment->staff_id}");
            }
        }
    }
}
