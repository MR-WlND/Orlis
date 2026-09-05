<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items'])->latest();

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                    ->orWhere('recipient_name', 'like', "%{$search}%")
                    ->orWhere('recipient_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(15)->withQueryString();
        $statuses = Order::STATUSES;

        // Tổng hợp số liệu nhanh
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('order_status', 'pending')->count(),
            'shipping' => Order::where('order_status', 'shipping')->count(),
            'revenue' => Order::where('order_status', 'delivered')->sum('grand_total'),
        ];

        return view('admin.orders.index', compact('orders', 'statuses', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.variant.product', 'coupon', 'statusLogs.changedByAdmin']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => ['required', 'in:'.implode(',', array_keys(Order::STATUSES))],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $oldStatus = $order->order_status;
        $newStatus = $request->order_status;

        if ($oldStatus === $newStatus) {
            return back()->with('error', 'Trạng thái không thay đổi.');
        }

        DB::transaction(function () use ($order, $oldStatus, $newStatus, $request) {
            $order->update(['order_status' => $newStatus]);

            OrderStatusLog::create([
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'note' => $request->note,
                'changed_by' => auth('admin')->id(),
            ]);
        });

        return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
    }

    public function destroy(Order $order)
    {
        if (! in_array($order->order_status, ['pending', 'cancelled'])) {
            return back()->with('error', 'Chỉ có thể xóa đơn hàng đang chờ hoặc đã hủy.');
        }

        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Đã xóa đơn hàng.');
    }
}
