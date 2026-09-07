<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Store;
use App\Services\AppointmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function __construct(protected AppointmentService $appointmentService) {}

    /**
     * Form đặt lịch hẹn thử nước hoa.
     */
    public function create()
    {
        $stores = Store::all();
        $timeSlots = [
            '09:00', '10:00', '11:00',
            '14:00', '15:00', '16:00', '17:00',
        ];

        return view('client.appointment.create', compact('stores', 'timeSlots'));
    }

    /**
     * Xử lý đặt lịch hẹn.
     */
    public function store(Request $request)
    {
        $request->validate([
            'store_id'         => ['required', 'exists:stores,id'],
            'appointment_date' => ['required', 'date', 'after:today'],
            'time_slot'        => ['required', 'string'],
            'service_type'     => ['required', 'in:consultation,trial,vip_service'],
            'contact_name'     => ['required', 'string', 'max:100'],
            'contact_phone'    => ['required', 'string', 'max:20'],
            'contact_email'    => ['required', 'email', 'max:150'],
            'note'             => ['nullable', 'string', 'max:500'],
        ], [
            'store_id.required'         => 'Vui lòng chọn cửa hàng.',
            'appointment_date.required' => 'Vui lòng chọn ngày hẹn.',
            'appointment_date.after'    => 'Ngày hẹn phải là ngày trong tương lai.',
            'time_slot.required'        => 'Vui lòng chọn khung giờ.',
            'service_type.required'     => 'Vui lòng chọn loại dịch vụ.',
            'contact_name.required'     => 'Vui lòng nhập họ tên.',
            'contact_phone.required'    => 'Vui lòng nhập số điện thoại xác nhận.',
            'contact_email.required'    => 'Vui lòng nhập địa chỉ email.',
            'contact_email.email'       => 'Email không hợp lệ.',
        ]);

        // Build full note including contact details
        $contactInfo = "Liên hệ: {$request->contact_name} | ĐT: {$request->contact_phone} | Email: {$request->contact_email}";
        $fullNote = $request->note ? "{$contactInfo}\n{$request->note}" : $contactInfo;

        try {
            $this->appointmentService->bookAppointment(
                userId: Auth::id(),
                storeId: $request->store_id,
                date: $request->appointment_date,
                timeSlot: $request->time_slot,
                serviceType: $request->service_type,
                variantIds: [],
                note: $fullNote,
            );
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()->route('customer.appointments')
            ->with('success', 'Đặt lịch hẹn thành công! Chúng tôi sẽ liên hệ xác nhận với bạn sớm.');
    }

    /**
     * Danh sách lịch hẹn của khách hàng.
     */
    public function index()
    {
        $appointments = Appointment::where('user_id', Auth::id())
            ->with('store')
            ->latest()
            ->paginate(10);

        return view('client.appointment.index', compact('appointments'));
    }

    /**
     * Hủy lịch hẹn.
     */
    public function cancel(Appointment $appointment)
    {
        abort_if($appointment->user_id !== Auth::id(), 403);

        if (! in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Không thể hủy lịch hẹn ở trạng thái này.');
        }

        $appointment->update([
            'status' => 'cancelled',
            'cancel_reason' => 'Khách hàng tự hủy',
        ]);

        return back()->with('success', 'Đã hủy lịch hẹn thành công.');
    }
}
