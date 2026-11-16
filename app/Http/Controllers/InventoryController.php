<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\InventoryMovement;
use App\Models\InventoryStock;
use App\Models\Product;
use App\Models\ProductContactLens;
use App\Models\ProductFrame;
use App\Models\ProductLens;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['brand', 'category', 'stocks.warehouse', 'frame', 'lens'])
            ->where('is_active', true)
            ->latest()
            ->paginate(15);

        $totalValuation = Product::with('stocks')->get()->reduce(function ($carry, $p) {
            return $carry + ($p->totalQuantity() * $p->purchase_price);
        }, 0);

        return view('inventory.index', compact('products', 'totalValuation'));
    }

    public function products(Request $request)
    {
        $type = $request->get('type');
        $query = Product::with(['brand', 'category', 'stocks', 'frame', 'lens', 'contactLens']);

        if ($type) {
            $query->where('product_type', $type);
        }

        $products = $query->latest()->paginate(15);
        $categories = Category::all();
        $brands = Brand::all();

        return view('products.index', compact('products', 'categories', 'brands', 'type'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'product_type' => 'required|in:frame,lens,contact_lens,sunglasses,accessory',
            'sku' => 'required|string|unique:products,sku',
            'barcode' => 'required|string|unique:products,barcode',
            'hsn_code' => 'required|string',
            'purchase_price' => 'required|numeric|min:0',
            'mrp' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric',
            'initial_stock' => 'nullable|integer|min:0',
        ]);

        $user = auth()->user() ?? User::first();
        $companyId = $user->company_id ?? 1;
        $branchId = $user->branch_id ?? 1;

        $product = Product::create([
            'company_id' => $companyId,
            'brand_id' => $validated['brand_id'],
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'barcode' => $validated['barcode'],
            'hsn_code' => $validated['hsn_code'],
            'product_type' => $validated['product_type'],
            'purchase_price' => $validated['purchase_price'],
            'mrp' => $validated['mrp'],
            'selling_price' => $validated['selling_price'],
            'tax_rate' => $validated['tax_rate'],
            'min_stock_alert' => 3,
        ]);

        // Attach specialized optical attributes
        if ($validated['product_type'] === 'frame') {
            ProductFrame::create([
                'product_id' => $product->id,
                'frame_shape' => $request->frame_shape ?? 'rectangle',
                'frame_material' => $request->frame_material ?? 'acetate',
                'rim_type' => $request->rim_type ?? 'full_rim',
                'gender' => $request->gender ?? 'unisex',
            ]);
        } elseif ($validated['product_type'] === 'lens') {
            ProductLens::create([
                'product_id' => $product->id,
                'lens_index' => $request->lens_index ?? '1.56',
                'lens_design' => $request->lens_design ?? 'single_vision',
                'lens_material' => $request->lens_material ?? 'cr39',
                'coating' => $request->coating ?? 'Hard Coat + AR',
            ]);
        }

        // Initialize stock if provided
        $qty = (int) ($validated['initial_stock'] ?? 0);
        if ($qty > 0) {
            $warehouse = Warehouse::where('branch_id', $branchId)->first() ?? Warehouse::first();
            if ($warehouse) {
                InventoryStock::create([
                    'company_id' => $companyId,
                    'branch_id' => $branchId,
                    'warehouse_id' => $warehouse->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'reserved_quantity' => 0,
                ]);

                InventoryMovement::create([
                    'company_id' => $companyId,
                    'branch_id' => $branchId,
                    'warehouse_id' => $warehouse->id,
                    'product_id' => $product->id,
                    'movement_type' => 'initial_stock',
                    'quantity' => $qty,
                    'balance_after' => $qty,
                    'unit_cost' => $product->purchase_price,
                    'notes' => 'Initial stock on product creation',
                    'created_by' => $user->id,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Product ' . $product->name . ' registered in optical catalog successfully!');
    }
}

