<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function create()
    {
        return view('admin.pos.create');
    }

    public function searchProducts(Request $request)
    {
        $q = $request->q;
        $variants = ProductVariant::with('product')
            ->whereHas('product', function($query) use ($q) {
                $query->where('name', 'like', "%{$q}%");
            })
            ->orWhere('sku', 'like', "%{$q}%")
            ->take(10)
            ->get();
            
        return response()->json($variants->map(function($v) {
            return [
                'id' => $v->id,
                'name' => $v->product->name . ' - ' . $v->size . ' ' . $v->color,
                'price' => $v->price,
                'stock' => $v->stock,
                'image' => $v->product->image_url // Assuming product has image_url or we can fallback
            ];
        }));
    }

    public function searchUsers(Request $request)
    {
        $q = $request->q;
        $users = User::where('role', 'customer')
            ->where(function($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('phone', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
            })
            ->take(5)
            ->get();
            
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_fee' => 'numeric|min:0',
            'discount' => 'numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $subtotal = 0;
            foreach ($request->items as $item) {
                $variant = ProductVariant::find($item['id']);
                $subtotal += $variant->price * $item['quantity'];
                
                // Deduct stock
                if ($variant->stock < $item['quantity']) {
                    throw new \Exception("Sản phẩm {$variant->product->name} không đủ tồn kho.");
                }
                $variant->decrement('stock', $item['quantity']);
            }

            $shipping = $request->shipping_fee ?? 0;
            $discount = $request->discount ?? 0;
            $grandTotal = $subtotal + $shipping - $discount;

            $order = Order::create([
                'order_code' => 'ORD' . strtoupper(uniqid()),
                'user_id' => $request->user_id,
                'subtotal' => $subtotal,
                'shipping_fee' => $shipping,
                'discount' => $discount,
                'grand_total' => $grandTotal,
                'payment_method' => 'cod',
                'payment_status' => 'unpaid',
                'order_status' => 'confirmed', // Auto confirmed if created via POS
                'shipping_address' => $request->shipping_address ?? 'Nhận tại cửa hàng',
                'customer_phone' => $request->customer_phone ?? '',
                'customer_name' => $request->customer_name ?? '',
            ]);

            foreach ($request->items as $item) {
                $variant = ProductVariant::find($item['id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $variant->id,
                    'quantity' => $item['quantity'],
                    'price' => $variant->price,
                ]);
            }

            // Create status log
            $order->statusLogs()->create([
                'status' => 'confirmed',
                'note' => 'Đơn hàng được tạo thủ công qua hệ thống POS/Telesales.',
            ]);

            DB::commit();
            return response()->json(['success' => true, 'order_id' => $order->id, 'message' => 'Tạo đơn hàng thành công!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
