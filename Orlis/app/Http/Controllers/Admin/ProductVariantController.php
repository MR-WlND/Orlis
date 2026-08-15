<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index($productId)
    {
        $product = Product::findOrFail($productId);
        $variants = $product->variants()->latest()->paginate(20);
        return view('admin.products.variants.index', compact('product', 'variants'));
    }

    public function create($productId)
    {
        $product = Product::findOrFail($productId);
        return view('admin.products.variants.form', compact('product'));
    }

    public function store(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        $request->validate([
            'sku' => 'required|string|max:50|unique:product_variants,sku',
            'color' => 'nullable|string|max:50',
            'size' => 'nullable|string|max:20',
            'stock_qty' => 'required|integer|min:0',
            'price_override' => 'nullable|numeric|min:0',
        ]);

        $product->variants()->create($request->all());

        return redirect()->route('admin.products.variants.index', $product->id)
            ->with('success', 'Thêm biến thể thành công.');
    }

    public function edit($productId, $variantId)
    {
        $product = Product::findOrFail($productId);
        $variant = ProductVariant::findOrFail($variantId);
        return view('admin.products.variants.form', compact('product', 'variant'));
    }

    public function update(Request $request, $productId, $variantId)
    {
        $product = Product::findOrFail($productId);
        $variant = ProductVariant::findOrFail($variantId);

        $request->validate([
            'sku' => 'required|string|max:50|unique:product_variants,sku,' . $variant->id,
            'color' => 'nullable|string|max:50',
            'size' => 'nullable|string|max:20',
            'stock_qty' => 'required|integer|min:0',
            'price_override' => 'nullable|numeric|min:0',
        ]);

        $variant->update($request->all());

        return redirect()->route('admin.products.variants.index', $product->id)
            ->with('success', 'Cập nhật biến thể thành công.');
    }

    public function destroy($productId, $variantId)
    {
        $variant = ProductVariant::findOrFail($variantId);
        $variant->delete();

        return back()->with('success', 'Xóa biến thể thành công.');
    }
}
