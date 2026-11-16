@extends('layouts.app', ['title' => 'Order #' . $order->order_number, 'header' => 'Sales Order: ' . $order->order_number])

@section('content')
<div class="space-y-8">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <a href="{{ route('orders.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">← Back to Orders</a>
        
        <div class="flex items-center gap-3">
            @if($order->invoices->count() > 0)
                <a href="{{ route('invoices.show', $order->invoices->first()->id) }}" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>View Tax Invoice</span>
                </a>
            @endif

            @if($order->order_status !== 'delivered')
                <form method="POST" action="{{ route('orders.deliver', $order->id) }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md shadow-emerald-600/30 transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Mark Customer Delivered</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Main Order Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Order Line Items & Optical Specifications -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Items Table -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 pb-4 border-b border-slate-100">Order Line Items</h3>

                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-600 uppercase font-bold border-b border-slate-100">
                            <tr>
                                <th class="py-3 px-4">Item & Description</th>
                                <th class="py-3 px-3 text-center">HSN</th>
                                <th class="py-3 px-3 text-center">Qty</th>
                                <th class="py-3 px-3 text-right">Unit Price</th>
                                <th class="py-3 px-3 text-right">GST %</th>
                                <th class="py-3 px-4 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="py-4 px-4">
                                        <div class="font-bold text-slate-900 text-sm">{{ $item->item_name }}</div>
                                        <div class="text-[11px] text-slate-500 capitalize">{{ $item->item_type }} {{ $item->eye_target !== 'none' ? '• Eye: ' . $item->eye_target : '' }}</div>
                                    </td>
                                    <td class="py-4 px-3 text-center font-mono">{{ $item->hsn_code }}</td>
                                    <td class="py-4 px-3 text-center font-bold">{{ $item->quantity }}</td>
                                    <td class="py-4 px-3 text-right font-mono">₹{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="py-4 px-3 text-right font-mono">{{ $item->tax_rate }}%</td>
                                    <td class="py-4 px-4 text-right font-extrabold text-slate-900 font-mono">₹{{ number_format($item->total_amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Financial Calculation Breakdown -->
                <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end text-xs">
                    <div class="w-72 space-y-2">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal:</span>
                            <span class="font-mono">₹{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Taxable Value:</span>
                            <span class="font-mono">₹{{ number_format($order->taxable_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Central GST (CGST):</span>
                            <span class="font-mono">₹{{ number_format($order->cgst_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>State GST (SGST):</span>
                            <span class="font-mono">₹{{ number_format($order->sgst_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between font-extrabold text-base text-slate-900 pt-2 border-t border-slate-200">
                            <span>Total Amount:</span>
                            <span class="text-teal-600 font-mono">₹{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between font-bold text-slate-700">
                            <span>Paid Amount:</span>
                            <span class="font-mono text-emerald-600">₹{{ number_format($order->paid_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between font-extrabold text-slate-900">
                            <span>Balance Due:</span>
                            <span class="font-mono {{ $order->balance_amount > 0 ? 'text-rose-600' : 'text-slate-400' }}">₹{{ number_format($order->balance_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Optical Prescription Linked -->
            @if($order->prescription)
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900">Linked Prescription</h3>
                        <a href="{{ route('prescriptions.show', $order->prescription->id) }}" class="text-xs font-bold text-blue-600 hover:underline">
                            {{ $order->prescription->prescription_number }}
                        </a>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-4 text-xs font-mono">
                        <div class="p-3 rounded-xl bg-blue-50/60 border border-blue-100">
                            <span class="font-bold text-blue-700 block mb-1">Right Eye (OD)</span>
                            {{ $order->prescription->formatPower('OD') }}
                        </div>
                        <div class="p-3 rounded-xl bg-teal-50/60 border border-teal-100">
                            <span class="font-bold text-teal-700 block mb-1">Left Eye (OS)</span>
                            {{ $order->prescription->formatPower('OS') }}
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <!-- Right Col: Customer, Payment Collector & Lab Status -->
        <div class="space-y-6">
            
            <!-- Customer Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 pb-3 border-b border-slate-100">Customer Details</h3>
                <div class="mt-4 text-xs space-y-2">
                    <p class="font-bold text-slate-900 text-sm">{{ $order->customer->name }}</p>
                    <p class="text-slate-600">📞 {{ $order->customer->phone }}</p>
                    @if($order->customer->email)
                        <p class="text-slate-600">✉️ {{ $order->customer->email }}</p>
                    @endif
                    <p class="text-slate-600">📍 {{ $order->customer->address ?? 'Mumbai, MH' }}</p>
                </div>
            </div>

            <!-- Lab Job Card Quick Link -->
            @if($order->labJobCard)
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900">Lens Lab Status</h3>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-800">
                            {{ str_replace('_', ' ', $order->labJobCard->stage) }}
                        </span>
                    </div>
                    <div class="mt-3 text-xs">
                        <p class="text-slate-500">Job #: <span class="font-mono font-bold text-slate-900">{{ $order->labJobCard->job_number }}</span></p>
                        <p class="text-slate-500 mt-1">Technician: <span class="font-semibold text-slate-800">{{ $order->labJobCard->technician->name ?? 'Unassigned' }}</span></p>
                        
                        <div class="mt-4">
                            <a href="{{ route('laboratory.show', $order->labJobCard->id) }}" class="block w-full py-2 bg-blue-50 hover:bg-blue-600 hover:text-white text-blue-600 font-bold rounded-xl text-center transition">
                                Open Lab Job Card →
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Collect Payment Form (if balance > 0) -->
            @if($order->balance_amount > 0)
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 pb-3 border-b border-slate-100">Collect Due Balance</h3>
                    
                    <form method="POST" action="{{ route('orders.payment', $order->id) }}" class="mt-4 space-y-3">
                        @csrf
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Amount to Collect (₹)</label>
                            <input type="number" step="0.01" name="amount" value="{{ $order->balance_amount }}" max="{{ $order->balance_amount }}" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Payment Mode</label>
                            <select name="payment_mode" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800">
                                <option value="cash">Cash</option>
                                <option value="upi">UPI / QR</option>
                                <option value="card">Card (POS Machine)</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                        </div>

                        <div>
                            <input type="text" name="transaction_reference" placeholder="Txn Ref / Auth Code..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        </div>

                        <button type="submit" class="w-full py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-md shadow-teal-600/30 transition">
                            Record Payment & Issue Receipt
                        </button>
                    </form>
                </div>
            @endif

        </div>

    </div>

</div>
@endsection

