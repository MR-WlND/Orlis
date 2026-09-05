<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Appointment;
use App\Services\AppointmentService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct(protected AppointmentService $appointmentService) {}

    /**
     * Danh sách tất cả lịch hẹn.
     */
    public function index(Request $request)
    {
        $query = Appointment::with(['user', 'store', 'staff'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('appointment_code', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        $appointments = $query->paginate(15)->withQueryString();

        $statuses = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'cancelled' => 'Đã hủy',
            'completed' => 'Hoàn thành',
        ];

        $stats = [
            'total' => Appointment::count(),
            'pending' => Appointment::where('status', 'pending')->count(),
            'today' => Appointment::whereDate('appointment_date', today())->count(),
            'confirmed' => Appointment::where('status', 'confirmed')->count(),
        ];

        return view('admin.appointments.index', compact('appointments', 'statuses', 'stats'));
    }

    /**
     * Chi tiết lịch hẹn.
     */
    public function show(Appointment $appointment)
    {
        $appointment->load(['user', 'store', 'staff', 'items.variant.product']);
        $staffList = Admin::where('role', 'staff')->where('status', true)->get();

        return view('admin.appointments.show', compact('appointment', 'staffList'));
    }

    /**
     * Phân công nhân viên phục vụ.
     */
    public function assignStaff(Request $request, Appointment $appointment)
    {
        $request->validate([
            'staff_id' => ['required', 'exists:admins,id'],
        ]);

        try {
            $this->appointmentService->assignStaff($appointment->id, $request->staff_id);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Đã phân công nhân viên và xác nhận lịch hẹn.');
    }

    /**
     * Cập nhật trạng thái lịch hẹn.
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => ['required', 'in:pending,confirmed,cancelled,completed'],
            'note' => ['nullable', 'string', 'max:300'],
        ]);

        $data = ['status' => $request->status];

        if ($request->filled('note')) {
            $data['cancel_reason'] = $request->note;
        }

        $appointment->update($data);

        return back()->with('success', 'Cập nhật trạng thái lịch hẹn thành công.');
    }
}
