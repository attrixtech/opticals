@extends('layouts.app', ['title' => 'Optical Inventory', 'header' => 'Optical Inventory & Stock Control'])

@section('content')
<div class="space-y-6">

    <!-- Top KPI Valuation Bar -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Stock Valuation</p>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-1">₹{{ number_format($totalValuation, 2) }}</h3>
                <p class="text-[11px] text-teal-600 font-semibold mt-0.5">Calculated at purchase cost</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-xl">
                📦
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Catalog SKUs</p>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $products->total() }}</h3>
                <p class="text-[11px] text-blue-600 font-semibold mt-0.5">Active frames & lenses</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl">
                👓
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Warehouse Depots</p>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-1">2</h3>
                <p class="text-[11px] text-indigo-600 font-semibold mt-0.5">Mumbai Central & Pune Store</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xl">
                🏢
            </div>
        </div>
    </div>

    <!-- Inventory Stock Register Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900">Stock Register & Barcode References</h3>
            <a href="{{ route('products.index') }}" class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Add Product to Catalog</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6">Barcode / SKU</th>
                        <th class="py-3.5 px-6">Product Name</th>
                        <th class="py-3.5 px-6">Category</th>
                        <th class="py-3.5 px-6 text-right">Cost Price</th>
                        <th class="py-3.5 px-6 text-right">Selling Price</th>
                        <th class="py-3.5 px-6 text-center">Available Stock</th>
                        <th class="py-3.5 px-6 text-center">Reserved (Lab)</th>
                        <th class="py-3.5 px-6 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($products as $prod)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 px-6 font-mono">
                                <div class="font-bold text-slate-900">{{ $prod->barcode }}</div>
                                <div class="text-[11px] text-slate-500">{{ $prod->sku }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900 text-sm">{{ $prod->name }}</div>
                                <div class="text-[11px] text-slate-500">{{ $prod->brand->name ?? 'Standard' }} • HSN {{ $prod->hsn_code }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-0.5 rounded text-[11px] font-semibold capitalize bg-slate-100 text-slate-700">
                                    {{ str_replace('_', ' ', $prod->product_type) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right font-mono">₹{{ number_format($prod->purchase_price, 2) }}</td>
                            <td class="py-4 px-6 text-right font-mono font-bold text-slate-900">₹{{ number_format($prod->selling_price, 2) }}</td>
                            <td class="py-4 px-6 text-center font-bold text-sm text-slate-900 font-mono">
                                {{ $prod->totalQuantity() }}
                            </td>
                            <td class="py-4 px-6 text-center font-mono text-amber-600 font-bold">
                                {{ $prod->stocks->sum('reserved_quantity') }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($prod->totalQuantity() <= $prod->min_stock_alert)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-100 text-rose-700">Low Stock</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700">In Stock</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $products->links() }}
        </div>
    </div>

</div>
@endsection

