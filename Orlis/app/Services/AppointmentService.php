<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\AppointmentItem;
use App\Models\StoreInventory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class AppointmentService
{
    public function bookAppointment(int $userId, int $storeId, string $date, string $timeSlot, array $variantIds, ?string $note = null): Appointment
    {
        $appointmentDate = Carbon::parse($date);
        $now = Carbon::now();

        // 1. Phân tích hàng tồn & Xác định xem có cần luân chuyển (needs_transfer)
        $hasItemsNeedingTransfer = false;
        $itemsData = [];

        foreach ($variantIds as $variantId) {
            // Check tồn kho tại store đăng ký
            $localInventory = DB::table('store_inventory')->where('store_id', $storeId)
                ->where('variant_id', $variantId)
                ->where('stock_qty', '>', 0)
                ->first();

            if ($localInventory) {
                $itemsData[] = [
                    'variant_id' => $variantId,
                    'source_store_id' => $storeId,
                    'needs_transfer' => false,
                ];
            } else {
                // Lấy kho khác có tồn nhiều nhất
                $otherInventory = DB::table('store_inventory')->where('store_id', '!=', $storeId)
                    ->where('variant_id', $variantId)
                    ->where('stock_qty', '>', 0)
                    ->orderBy('stock_qty', 'desc')
                    ->first();

                if (!$otherInventory) {
                    throw new Exception("Sản phẩm mã #{$variantId} hiện đã hết hàng trên toàn hệ thống.");
                }

                $hasItemsNeedingTransfer = true;
                $itemsData[] = [
                    'variant_id' => $variantId,
                    'source_store_id' => $otherInventory->store_id,
                    'needs_transfer' => true,
                ];
            }
        }

        // 2. Rào chắn Lead Time
        $minDays = $hasItemsNeedingTransfer ? 3 : 1;
        if ($now->startOfDay()->diffInDays($appointmentDate->startOfDay(), false) < $minDays) {
            $msg = $hasItemsNeedingTransfer 
                ? "Sản phẩm yêu cầu luân chuyển giữa các Showroom. Vui lòng đặt lịch trước ít nhất 3 ngày." 
                : "Vui lòng đặt lịch hẹn trước ít nhất 24 giờ.";
            throw new Exception($msg);
        }

        // 3. Khởi tạo Đặt lịch trong Transaction
        return DB::transaction(function () use ($userId, $storeId, $appointmentDate, $timeSlot, $hasItemsNeedingTransfer, $itemsData, $note) {
            $appointment = Appointment::create([
                'appointment_code' => 'APT_' . strtoupper(uniqid()),
                'user_id' => $userId,
                'store_id' => $storeId,
                'appointment_date' => $appointmentDate->toDateString(),
                'time_slot' => $timeSlot,
                'appointment_datetime' => $appointmentDate->toDateString() . ' ' . $timeSlot . ':00',
                'status' => 'pending',
                'transfer_status' => $hasItemsNeedingTransfer ? 'needs_transfer' : 'available',
                'note' => $note,
            ]);

            foreach ($itemsData as $item) {
                $appointment->items()->create($item);
            }

            return $appointment;
        });
    }

    public function assignStaff(int $appointmentId, int $staffId): Appointment
    {
        $appointment = Appointment::findOrFail($appointmentId);

        // Check Double-booking Staff
        $isBusy = Appointment::where('staff_id', $staffId)
            ->where('appointment_date', $appointment->appointment_date)
            ->where('time_slot', $appointment->time_slot)
            ->where('status', 'confirmed')
            ->where('id', '!=', $appointment->id)
            ->exists();

        if ($isBusy) {
            throw new Exception("Nhân viên này đã có lịch tiếp đón VIP vào khung giờ {$appointment->time_slot} ngày {$appointment->appointment_date->format('d/m/Y')}.");
        }

        $appointment->update([
            'staff_id' => $staffId,
            'status' => 'confirmed',
        ]);

        return $appointment;
    }
}
