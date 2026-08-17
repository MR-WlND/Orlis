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
            Log::info("Sending appointment reminder email to User #{$appointment->user_id} for Appointment #{$appointment->appointment_code}");

            // Gửi notification nhắc nhở Staff phụ trách
            if ($appointment->staff_id) {
                Log::info("Sending staff reminder notification to Staff #{$appointment->staff_id}");
            }
        }
    }
}
