<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function dashboard()
    {
        $supplierId = Auth::id();
        
        // Fetch purchase orders assigned to this supplier
        $purchaseOrders = PurchaseOrder::where('supplier_id', $supplierId)
            ->with(['items.productVariant.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        // Calculate stats
        $totalOrders = PurchaseOrder::where('supplier_id', $supplierId)->count();
        $pendingOrders = PurchaseOrder::where('supplier_id', $supplierId)->where('status', 'pending')->count();
        $completedOrders = PurchaseOrder::where('supplier_id', $supplierId)->where('status', 'completed')->count();
        $totalRevenue = PurchaseOrder::where('supplier_id', $supplierId)->where('status', 'completed')->sum('total_amount');

        return view('supplier.dashboard', compact(
            'purchaseOrders', 'totalOrders', 'pendingOrders', 'completedOrders', 'totalRevenue'
        ));
    }

    public function updateStatus(Request $request, PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->supplier_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:confirmed,shipped,cancelled'
        ]);

        $purchaseOrder->status = $request->status;
        $purchaseOrder->save();

        return back()->with('success', 'Đã cập nhật trạng thái đơn nhập hàng thành: ' . $request->status);
    }
}
