<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = now()->month;
        $lastMonth = now()->subMonth()->month;
        $currentYear = now()->year;

        // Doanh thu tháng này và tháng trước
        $revenueThisMonth = Order::where('order_status', 'delivered')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('grand_total');

        $revenueLastMonth = Order::where('order_status', 'delivered')
            ->whereMonth('created_at', $lastMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('grand_total');

        // Tổng đơn hàng tháng này
        $ordersThisMonth = Order::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        $ordersLastMonth = Order::whereMonth('created_at', $lastMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        // Khách hàng hoạt động (có đơn hàng trong 30 ngày qua)
        $activeCustomers = User::whereHas('orders', function ($q) {
            $q->where('created_at', '>=', now()->subDays(30));
        })->count();

        $activeCustomersLastMonth = User::whereHas('orders', function ($q) {
            $q->whereBetween('created_at', [now()->subDays(60), now()->subDays(30)]);
        })->count();

        // Tính % thay đổi
        $revenueChange = $revenueLastMonth > 0
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1)
            : 0;

        $ordersChange = $ordersLastMonth > 0
            ? round((($ordersThisMonth - $ordersLastMonth) / $ordersLastMonth) * 100, 1)
            : 0;

        $customersChange = $activeCustomersLastMonth > 0
            ? round((($activeCustomers - $activeCustomersLastMonth) / $activeCustomersLastMonth) * 100, 1)
            : 0;

        // Doanh thu 7 ngày gần nhất cho biểu đồ
        $chartData = Order::where('order_status', 'delivered')
            ->where('created_at', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(grand_total) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $chartLabels = [];
        $chartValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('d/m');
            $chartValues[] = $chartData->get($date)?->total ?? 0;
        }

        // Đơn hàng mới nhất
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // Sản phẩm bán chạy
        $topProducts = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('product_variants', 'product_variants.id', '=', 'order_items.variant_id')
            ->join('products', 'products.id', '=', 'product_variants.product_id')
            ->where('orders.order_status', 'delivered')
            ->select('products.name', 'products.thumbnail', DB::raw('SUM(order_items.quantity) as total_sold'), DB::raw('SUM(order_items.price * order_items.quantity) as revenue'))
            ->groupBy('products.id', 'products.name', 'products.thumbnail')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'revenueThisMonth', 'revenueChange',
            'ordersThisMonth', 'ordersChange',
            'activeCustomers', 'customersChange',
            'chartLabels', 'chartValues',
            'recentOrders', 'topProducts'
        ));
    }
}
