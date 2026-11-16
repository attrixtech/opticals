@extends('layouts.app', ['title' => 'Product Catalog', 'header' => 'Optical Catalog (Frames, Lenses & Accessories)'])

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar & Modal Trigger -->
    <div x-data="{ openModal: false }">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <!-- Filter Tabs -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0 w-full sm:w-auto">
                <a href="{{ route('products.index') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ !$type ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">All Items</a>
                <a href="{{ route('products.index', ['type' => 'frame']) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $type === 'frame' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Frames</a>
                <a href="{{ route('products.index', ['type' => 'lens']) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $type === 'lens' ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Lenses</a>
                <a href="{{ route('products.index', ['type' => 'contact_lens']) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $type === 'contact_lens' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Contact Lenses</a>
                <a href="{{ route('products.index', ['type' => 'sunglasses']) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $type === 'sunglasses' ? 'bg-purple-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Sunglasses</a>
            </div>

            <button @click="openModal = true" class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold shadow-md shadow-purple-600/30 transition flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Add Optical Product</span>
            </button>
        </div>

        <!-- Add Product Modal -->
        <div x-show="openModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div @click.away="openModal = false" class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-900">Add New Optical Product</h3>
                    <button @click="openModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form method="POST" action="{{ route('products.store') }}" class="space-y-4 text-xs">
                    @csrf

                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Product Name *</label>
                            <input type="text" name="name" required placeholder="e.g. Ray-Ban Aviator RX6489" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                        </div>

                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Brand *</label>
                            <select name="brand_id" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                                @foreach($brands as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Category *</label>
                            <select name="category_id" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Product Type *</label>
                            <select name="product_type" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                                <option value="frame">Frame</option>
                                <option value="lens">Ophthalmic Lens</option>
                                <option value="contact_lens">Contact Lens</option>
                                <option value="sunglasses">Sunglasses</option>
                                <option value="accessory">Accessory</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">HSN Code *</label>
                            <input type="text" name="hsn_code" value="9003" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                        </div>

                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">SKU *</label>
                            <input type="text" name="sku" required placeholder="e.g. RB-6489-GLD" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl font-mono">
                        </div>

                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Barcode *</label>
                            <input type="text" name="barcode" required placeholder="e.g. 805289998877" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl font-mono">
                        </div>

                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Cost Price (₹) *</label>
                            <input type="number" step="0.01" name="purchase_price" required placeholder="0.00" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                        </div>

                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">MRP (₹) *</label>
                            <input type="number" step="0.01" name="mrp" required placeholder="0.00" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                        </div>

                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Selling Price (₹) *</label>
                            <input type="number" step="0.01" name="selling_price" required placeholder="0.00" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold">
                        </div>

                        <div>
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">GST Tax Rate (%) *</label>
                            <select name="tax_rate" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold">
                                <option value="12.00">12% (Standard Optical)</option>
                                <option value="18.00">18% (Sunglasses / Solution)</option>
                                <option value="5.00">5% (Concessional)</option>
                                <option value="0.00">0% (Exempt)</option>
                            </select>
                        </div>

                        <div class="col-span-2">
                            <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Initial Stock Units</label>
                            <input type="number" name="initial_stock" value="10" min="0" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button" @click="openModal = false" class="px-4 py-2 border border-slate-200 rounded-xl text-slate-600 font-bold">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-purple-600 text-white rounded-xl font-bold shadow-md shadow-purple-600/30">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Product Grid Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($products as $p)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <div class="flex items-center justify-between text-[11px] mb-2">
                        <span class="font-extrabold text-purple-600 uppercase">{{ $p->brand->name ?? 'Brand' }}</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-mono bg-slate-100 text-slate-600 font-semibold">{{ $p->sku }}</span>
                    </div>

                    <h4 class="font-bold text-slate-900 text-sm line-clamp-2">{{ $p->name }}</h4>
                    <p class="text-[11px] text-slate-400 font-mono mt-1">Barcode: {{ $p->barcode }}</p>

                    @if($p->frame)
                        <div class="mt-3 flex flex-wrap gap-1 text-[10px]">
                            <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-semibold uppercase">{{ $p->frame->frame_shape }}</span>
                            <span class="px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 font-semibold uppercase">{{ $p->frame->frame_material }}</span>
                            <span class="px-2 py-0.5 rounded bg-teal-50 text-teal-700 font-semibold uppercase">{{ $p->frame->rim_type }}</span>
                        </div>
                    @elseif($p->lens)
                        <div class="mt-3 flex flex-wrap gap-1 text-[10px]">
                            <span class="px-2 py-0.5 rounded bg-teal-50 text-teal-700 font-semibold">Index {{ $p->lens->lens_index }}</span>
                            <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-semibold">{{ $p->lens->coating }}</span>
                        </div>
                    @endif
                </div>

                <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <div class="text-[11px] text-slate-400 line-through">MRP: ₹{{ number_format($p->mrp, 2) }}</div>
                        <div class="text-base font-extrabold text-slate-900">₹{{ number_format($p->selling_price, 2) }}</div>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-slate-400 block uppercase font-bold">Stock</span>
                        <span class="font-bold text-xs font-mono {{ $p->totalQuantity() <= $p->min_stock_alert ? 'text-rose-600' : 'text-emerald-600' }}">
                            {{ $p->totalQuantity() }} units
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm">
        {{ $products->links() }}
    </div>

</div>
@endsection

