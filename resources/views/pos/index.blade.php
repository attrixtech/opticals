@extends('layouts.app', ['title' => 'Optical Point of Sale', 'header' => 'Optical Point of Sale (POS)'])

@section('content')
<div x-data="opticalPos()" x-init="init()" class="h-full flex flex-col lg:flex-row gap-6">

    <!-- Left Column: Product Catalog & Quick Scanner -->
    <div class="flex-1 flex flex-col bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 overflow-hidden">
        
        <!-- Header Filters -->
        <div class="flex flex-col sm:flex-row gap-3 pb-5 border-b border-slate-100">
            <!-- Search / Scan Barcode -->
            <div class="relative flex-1">
                <input 
                    type="text" 
                    x-model="searchQuery" 
                    @keydown.enter.prevent="scanBarcode()"
                    placeholder="Search by Frame Name, SKU, or Scan Barcode..."
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition"
                >
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            <!-- Category Filter Pills -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0">
                <button 
                    type="button" 
                    @click="activeCategory = 'all'"
                    :class="activeCategory === 'all' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-3 py-2 rounded-lg text-xs font-semibold whitespace-nowrap transition"
                >
                    All Items
                </button>
                <button 
                    type="button" 
                    @click="activeCategory = 'frame'"
                    :class="activeCategory === 'frame' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-3 py-2 rounded-lg text-xs font-semibold whitespace-nowrap transition"
                >
                    Frames
                </button>
                <button 
                    type="button" 
                    @click="activeCategory = 'lens'"
                    :class="activeCategory === 'lens' ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-3 py-2 rounded-lg text-xs font-semibold whitespace-nowrap transition"
                >
                    Lenses
                </button>
                <button 
                    type="button" 
                    @click="activeCategory = 'contact_lens'"
                    :class="activeCategory === 'contact_lens' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-3 py-2 rounded-lg text-xs font-semibold whitespace-nowrap transition"
                >
                    Contact Lenses
                </button>
                <button 
                    type="button" 
                    @click="activeCategory = 'sunglasses'"
                    :class="activeCategory === 'sunglasses' ? 'bg-purple-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-3 py-2 rounded-lg text-xs font-semibold whitespace-nowrap transition"
                >
                    Sunglasses
                </button>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="flex-1 overflow-y-auto mt-4 pr-1">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <template x-for="p in filteredProducts" :key="p.id">
                    <div 
                        @click="addToCart(p)"
                        class="p-4 rounded-xl border border-slate-200/90 hover:border-teal-500 hover:shadow-md cursor-pointer transition group bg-white flex flex-col justify-between"
                    >
                        <div>
                            <div class="flex items-center justify-between text-[11px] mb-1.5">
                                <span class="font-bold text-slate-500 uppercase" x-text="p.brand ? p.brand.name : 'Optical'"></span>
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-mono bg-slate-100 text-slate-600" x-text="p.sku"></span>
                            </div>
                            <h4 class="font-bold text-sm text-slate-900 group-hover:text-teal-600 transition line-clamp-2" x-text="p.name"></h4>
                            
                            <!-- Optical Specs Tag -->
                            <div class="mt-2 flex flex-wrap gap-1 text-[11px] text-slate-500">
                                <template x-if="p.frame">
                                    <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-semibold" x-text="p.frame.frame_shape + ' • ' + p.frame.rim_type"></span>
                                </template>
                                <template x-if="p.lens">
                                    <span class="px-2 py-0.5 rounded bg-teal-50 text-teal-700 font-semibold" x-text="'Index ' + p.lens.lens_index"></span>
                                </template>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-xs text-slate-400 line-through mr-1" x-text="'₹' + Number(p.mrp).toFixed(2)"></span>
                                <span class="font-extrabold text-slate-900 text-base" x-text="'₹' + Number(p.selling_price).toFixed(2)"></span>
                            </div>
                            <button type="button" class="w-8 h-8 rounded-lg bg-teal-50 group-hover:bg-teal-600 text-teal-600 group-hover:text-white flex items-center justify-center transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Right Column: Checkout, Prescription Link & Indian GST Cart -->
    <div class="w-full lg:w-[460px] bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between">
        
        <form method="POST" action="{{ route('pos.store') }}" @submit="validateSubmit($event)">
            @csrf

            <!-- Customer & Prescription Selector -->
            <div class="space-y-4 pb-5 border-b border-slate-100">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Select Optical Customer *</label>
                    <select 
                        name="customer_id" 
                        x-model="selectedCustomerId" 
                        @change="loadCustomerPrescriptions()"
                        required 
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-teal-500 focus:bg-white transition"
                    >
                        <option value="">-- Choose Customer --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" data-state="{{ $c->state_code ?? '27' }}">{{ $c->name }} ({{ $c->phone }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Attached Prescription Dropdown -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Link Prescription (OD/OS)</label>
                        <a href="{{ route('prescriptions.create') }}" target="_blank" class="text-[11px] font-bold text-blue-600 hover:text-blue-700">+ New Rx</a>
                    </div>
                    <select 
                        name="prescription_id" 
                        x-model="selectedPrescriptionId" 
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-teal-500 focus:bg-white transition"
                    >
                        <option value="">-- No Prescription Linked / OTC Sale --</option>
                        <template x-for="rx in customerPrescriptions" :key="rx.id">
                            <option :value="rx.id" x-text="rx.prescription_number + ' (' + rx.prescription_date + ') - ' + (rx.od_sph || '0') + '/' + (rx.os_sph || '0')"></option>
                        </template>
                    </select>
                </div>
            </div>

            <!-- Cart Line Items -->
            <div class="py-4 flex-1 overflow-y-auto max-h-64 space-y-3">
                <template x-if="cart.length === 0">
                    <div class="py-8 text-center text-slate-400 text-xs">
                        <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Click product items on the left to add to sale order.
                    </div>
                </template>

                <template x-for="(item, index) in cart" :key="index">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 flex flex-col gap-2">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex-1">
                                <h5 class="font-bold text-xs text-slate-900" x-text="item.item_name"></h5>
                                <div class="text-[10px] text-slate-500 font-mono mt-0.5">HSN: <span x-text="item.hsn_code"></span> • GST: <span x-text="item.tax_rate + '%'"></span></div>
                            </div>
                            <button type="button" @click="removeItem(index)" class="text-slate-400 hover:text-rose-500 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <!-- Price & Qty Controls -->
                        <div class="flex items-center justify-between pt-1">
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-slate-500">Qty:</span>
                                <input type="number" min="1" x-model.number="item.quantity" class="w-14 p-1 bg-white border border-slate-200 rounded text-center text-xs font-bold">
                            </div>

                            <div class="text-right font-extrabold text-sm text-slate-900" x-text="'₹' + (item.unit_price * item.quantity).toFixed(2)"></div>
                        </div>

                        <!-- Hidden form inputs for submission -->
                        <input type="hidden" :name="'items['+index+'][product_id]'" :value="item.product_id">
                        <input type="hidden" :name="'items['+index+'][item_name]'" :value="item.item_name">
                        <input type="hidden" :name="'items['+index+'][item_type]'" :value="item.item_type">
                        <input type="hidden" :name="'items['+index+'][hsn_code]'" :value="item.hsn_code">
                        <input type="hidden" :name="'items['+index+'][eye_target]'" :value="item.eye_target">
                        <input type="hidden" :name="'items['+index+'][quantity]'" :value="item.quantity">
                        <input type="hidden" :name="'items['+index+'][unit_price]'" :value="item.unit_price">
                        <input type="hidden" :name="'items['+index+'][discount_amount]'" :value="item.discount_amount">
                        <input type="hidden" :name="'items['+index+'][tax_rate]'" :value="item.tax_rate">
                    </div>
                </template>
            </div>

            <!-- GST & Order Financial Summary -->
            <div class="pt-4 border-t border-slate-100 space-y-2 text-xs">
                <div class="flex justify-between text-slate-500">
                    <span>Taxable Subtotal</span>
                    <span class="font-semibold text-slate-800" x-text="'₹' + subtotal.toFixed(2)"></span>
                </div>
                <div class="flex justify-between text-slate-500">
                    <span>Output CGST (6%)</span>
                    <span class="font-semibold text-slate-800" x-text="'₹' + cgst.toFixed(2)"></span>
                </div>
                <div class="flex justify-between text-slate-500">
                    <span>Output SGST (6%)</span>
                    <span class="font-semibold text-slate-800" x-text="'₹' + sgst.toFixed(2)"></span>
                </div>
                <div class="flex justify-between text-base font-extrabold text-slate-900 pt-2 border-t border-slate-200">
                    <span>Grand Total (Incl. GST)</span>
                    <span class="text-teal-600" x-text="'₹' + grandTotal.toFixed(2)"></span>
                </div>
            </div>

            <!-- Advance Payment / Settlement -->
            <div class="mt-4 p-3.5 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Advance Deposit</label>
                        <input 
                            type="number" 
                            name="advance_amount" 
                            step="0.01" 
                            x-model.number="advanceAmount"
                            placeholder="0.00" 
                            class="w-full p-2 bg-white border border-slate-200 rounded-lg text-sm font-bold text-slate-900"
                        >
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Payment Mode</label>
                        <select name="payment_mode" class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-800">
                            <option value="upi">UPI / QR Code</option>
                            <option value="cash">Cash</option>
                            <option value="card">Card (POS)</option>
                            <option value="bank_transfer">Net Banking</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-between text-xs font-bold pt-1">
                    <span class="text-slate-500">Balance on Fitting/Delivery:</span>
                    <span :class="balanceAmount > 0 ? 'text-rose-600' : 'text-emerald-600'" x-text="'₹' + balanceAmount.toFixed(2)"></span>
                </div>
            </div>

            <!-- Order Confirmation Button -->
            <div class="mt-5">
                <button 
                    type="submit" 
                    :disabled="cart.length === 0 || !selectedCustomerId"
                    :class="cart.length > 0 && selectedCustomerId ? 'bg-teal-600 hover:bg-teal-700 shadow-teal-600/30' : 'bg-slate-300 cursor-not-allowed'"
                    class="w-full py-3.5 rounded-xl text-white font-extrabold text-sm shadow-md transition flex items-center justify-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Confirm Order & Generate Lab Job</span>
                </button>
            </div>
        </form>

    </div>

</div>

<script>
function opticalPos() {
    return {
        products: @json($products),
        searchQuery: '',
        activeCategory: 'all',
        cart: [],
        selectedCustomerId: '',
        selectedPrescriptionId: '',
        customerPrescriptions: [],
        advanceAmount: 0,

        init() {
            // Pre-seed cart with 1 frame for quick demonstration if desired
        },

        get filteredProducts() {
            return this.products.filter(p => {
                const matchCategory = this.activeCategory === 'all' || p.product_type === this.activeCategory;
                const q = this.searchQuery.toLowerCase().trim();
                const matchQuery = !q || 
                    p.name.toLowerCase().includes(q) || 
                    p.sku.toLowerCase().includes(q) || 
                    (p.barcode && p.barcode.includes(q));
                return matchCategory && matchQuery;
            });
        },

        scanBarcode() {
            const query = this.searchQuery.trim();
            if (!query) return;
            const match = this.products.find(p => p.barcode === query || p.sku.toLowerCase() === query.toLowerCase());
            if (match) {
                this.addToCart(match);
                this.searchQuery = '';
            }
        },

        addToCart(product) {
            const existing = this.cart.find(item => item.product_id === product.id);
            if (existing) {
                existing.quantity++;
            } else {
                this.cart.push({
                    product_id: product.id,
                    item_name: product.name,
                    item_type: product.product_type,
                    hsn_code: product.hsn_code || '9003',
                    eye_target: product.product_type === 'lens' ? 'both' : 'none',
                    quantity: 1,
                    unit_price: parseFloat(product.selling_price),
                    discount_amount: 0.00,
                    tax_rate: parseFloat(product.tax_rate) || 12.00,
                });
            }
            if (this.advanceAmount === 0) {
                this.advanceAmount = this.grandTotal;
            }
        },

        removeItem(index) {
            this.cart.splice(index, 1);
        },

        loadCustomerPrescriptions() {
            if (!this.selectedCustomerId) {
                this.customerPrescriptions = [];
                return;
            }
            fetch(`/pos/prescriptions/${this.selectedCustomerId}`)
                .then(res => res.json())
                .then(data => {
                    this.customerPrescriptions = data;
                    if (data.length > 0) {
                        this.selectedPrescriptionId = data[0].id;
                    }
                });
        },

        get subtotal() {
            return this.cart.reduce((sum, item) => sum + (item.unit_price * item.quantity), 0);
        },

        get cgst() {
            return this.cart.reduce((sum, item) => {
                const taxable = (item.unit_price * item.quantity) - (item.discount_amount || 0);
                return sum + (taxable * (item.tax_rate / 200));
            }, 0);
        },

        get sgst() {
            return this.cgst;
        },

        get grandTotal() {
            return this.subtotal + this.cgst + this.sgst;
        },

        get balanceAmount() {
            const bal = this.grandTotal - (this.advanceAmount || 0);
            return bal > 0 ? bal : 0;
        },

        validateSubmit(event) {
            if (this.cart.length === 0) {
                alert('Please select at least one frame or lens item.');
                event.preventDefault();
            }
            if (!this.selectedCustomerId) {
                alert('Please choose a customer.');
                event.preventDefault();
            }
        }
    };
}
</script>
@endsection

