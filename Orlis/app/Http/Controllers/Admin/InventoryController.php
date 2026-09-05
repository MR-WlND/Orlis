<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Models\Store;
use App\Models\StoreInventory;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function __construct(protected InventoryService $inventoryService) {}

    /**
     * Danh sách tồn kho toàn hệ thống.
     */
    public function index(Request $request)
    {
        $stores = Store::all();
        $selectedStore = $request->filled('store_id') ? $stores->find($request->store_id) : null;

        $query = DB::table('store_inventory as si')
            ->join('product_variants as pv', 'pv.id', '=', 'si.variant_id')
            ->join('products as p', 'p.id', '=', 'pv.product_id')
            ->join('stores as s', 's.id', '=', 'si.store_id')
            ->select(
                'si.id',
                'si.store_id',
                's.name as store_name',
                'si.variant_id',
                'p.name as product_name',
                'p.thumbnail',
                'pv.sku',
                'si.stock_qty',
                'si.reserved_qty',
                DB::raw('(si.stock_qty - si.reserved_qty) as available_qty'),
                'si.updated_at',
            );

        if ($request->filled('store_id')) {
            $query->where('si.store_id', $request->store_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('p.name', 'like', "%{$search}%")
                    ->orWhere('pv.sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('low_stock')) {
            $query->whereRaw('(si.stock_qty - si.reserved_qty) <= 5');
        }

        $inventory = $query->orderBy('p.name')->paginate(20)->withQueryString();

        // Stats
        $stats = [
            'total_variants' => DB::table('store_inventory')->distinct('variant_id')->count('variant_id'),
            'low_stock_count' => DB::table('store_inventory')->whereRaw('(stock_qty - reserved_qty) <= 5')->count(),
            'out_of_stock' => DB::table('store_inventory')->whereRaw('(stock_qty - reserved_qty) <= 0')->count(),
            'total_stores' => $stores->count(),
        ];

        return view('admin.inventory.index', compact('inventory', 'stores', 'stats'));
    }

    /**
     * Form thêm/cập nhật tồn kho cho 1 variant tại 1 store.
     */
    public function upsert(Request $request)
    {
        $request->validate([
            'store_id' => ['required', 'exists:stores,id'],
            'variant_id' => ['required', 'exists:product_variants,id'],
            'stock_qty' => ['required', 'integer', 'min:0'],
            'action' => ['required', 'in:set,add,subtract'],
        ]);

        $record = StoreInventory::where('store_id', $request->store_id)
            ->where('variant_id', $request->variant_id)
            ->first();

        DB::transaction(function () use ($request, $record) {
            if (! $record) {
                StoreInventory::create([
                    'store_id' => $request->store_id,
                    'variant_id' => $request->variant_id,
                    'stock_qty' => $request->stock_qty,
                    'reserved_qty' => 0,
                ]);

                return;
            }

            match ($request->action) {
                'set' => $record->update(['stock_qty' => $request->stock_qty]),
                'add' => $record->increment('stock_qty', $request->stock_qty),
                'subtract' => $record->decrement('stock_qty', min($request->stock_qty, max(0, $record->stock_qty - $record->reserved_qty))),
            };
        });

        return back()->with('success', 'Đã cập nhật tồn kho thành công.');
    }

    /**
     * Xem tồn kho theo sản phẩm/variant.
     */
    public function showVariant(int $variantId)
    {
        $variant = ProductVariant::with('product')->findOrFail($variantId);

        $inventory = StoreInventory::where('variant_id', $variantId)
            ->with('store')
            ->get();

        $stores = Store::all();

        return view('admin.inventory.variant', compact('variant', 'inventory', 'stores'));
    }

    /**
     * Admin điều chuyển hàng giữa các kho.
     */
    public function transfer(Request $request)
    {
        $request->validate([
            'from_store_id' => ['required', 'exists:stores,id', 'different:to_store_id'],
            'to_store_id' => ['required', 'exists:stores,id'],
            'variant_id' => ['required', 'exists:product_variants,id'],
            'qty' => ['required', 'integer', 'min:1'],
        ]);

        $from = StoreInventory::where('store_id', $request->from_store_id)
            ->where('variant_id', $request->variant_id)
            ->first();

        if (! $from || ($from->stock_qty - $from->reserved_qty) < $request->qty) {
            return back()->with('error', 'Kho nguồn không đủ hàng để điều chuyển.');
        }

        DB::transaction(function () use ($request, $from) {
            $from->decrement('stock_qty', $request->qty);

            StoreInventory::updateOrCreate(
                ['store_id' => $request->to_store_id, 'variant_id' => $request->variant_id],
                ['stock_qty' => DB::raw("stock_qty + {$request->qty}"), 'reserved_qty' => DB::raw('COALESCE(reserved_qty, 0)')],
            );
        });

        return back()->with('success', "Đã điều chuyển {$request->qty} sản phẩm thành công.");
    }
}
