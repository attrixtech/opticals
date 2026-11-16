@extends('layouts.app', ['title' => 'POS Cashier Counter', 'header' => 'POS Cashier & Front Desk Counter'])

@section('content')
<div class="space-y-8">

    <!-- Top Action Banner -->
    <div class="bg-gradient-to-r from-teal-700 to-emerald-800 rounded-2xl p-6 text-white flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-lg shadow-teal-700/20">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full bg-teal-500/30 text-teal-200 text-xs font-semibold uppercase tracking-wider">POS Counter 1</span>
                <span class="text-xs text-teal-100">Mumbai Lower Parel Showroom</span>
            </div>
            <h2 class="text-xl font-black mt-1">Cashier Station: {{ $user->name }}</h2>
            <p class="text-xs text-teal-100 mt-0.5">Quick barcode checkout, advance deposits, order delivery balance collection, and daily cash balancing.</p>
        </div>

        <a href="{{ route('pos.index') }}" class="px-5 py-3 rounded-xl bg-white hover:bg-slate-100 text-teal-800 font-extrabold text-xs shadow-md transition flex items-center gap-2">
            <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>+ Open Optical POS Terminal</span>
        </a>
    </div>

    <!-- Daily Financial & Register Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Today's Total Collected</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">₹{{ number_format($todayPayments, 2) }}</h3>
                <p class="text-[11px] text-teal-600 font-medium mt-0.5">Across all tender modes</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-xl">
                ₹
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Cash in Drawer</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">₹{{ number_format($cashPayments, 2) }}</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Opening Float: ₹5,000.00</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xl">
                💵
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">UPI / QR Collections</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">₹{{ number_format($upiPayments, 2) }}</h3>
                <p class="text-[11px] text-indigo-600 font-medium mt-0.5">Direct bank settlements</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xl">
                📱
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Card POS Machine</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">₹{{ number_format($cardPayments, 2) }}</h3>
                <p class="text-[11px] text-purple-600 font-medium mt-0.5">Credit / Debit cards</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xl">
                💳
            </div>
        </div>

    </div>

    <!-- Ready for Customer Pickup & Balance Settlement -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900">Spectacles Ready for Customer Delivery</h3>
                <p class="text-xs text-slate-500 mt-0.5">QC-passed spectacles waiting for face fitting and final balance settlement.</p>
            </div>
            <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">{{ $readyForDeliveryOrders->count() }} Orders Ready</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6">Order #</th>
                        <th class="py-3.5 px-6">Customer & Phone</th>
                        <th class="py-3.5 px-6">Ordered Frames & Lenses</th>
                        <th class="py-3.5 px-6 text-right">Order Total</th>
                        <th class="py-3.5 px-6 text-right">Balance Due</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($readyForDeliveryOrders as $ord)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 px-6 font-mono font-bold text-blue-600">
                                <a href="{{ route('orders.show', $ord->id) }}" class="hover:underline">{{ $ord->order_number }}</a>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900">{{ $ord->customer->name }}</div>
                                <div class="text-[11px] text-slate-500">{{ $ord->customer->phone }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-slate-800 font-semibold">{{ $ord->items->pluck('item_name')->join(', ') }}</div>
                            </td>
                            <td class="py-4 px-6 text-right font-mono font-bold text-slate-900">
                                ₹{{ number_format($ord->total_amount, 2) }}
                            </td>
                            <td class="py-4 px-6 text-right font-mono font-extrabold text-sm {{ $ord->balance_amount > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                ₹{{ number_format($ord->balance_amount, 2) }}
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <a href="{{ route('orders.show', $ord->id) }}" class="px-3.5 py-1.5 rounded-lg bg-teal-50 hover:bg-teal-600 hover:text-white text-teal-700 text-xs font-bold transition">
                                    Deliver & Settle →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">No orders pending customer delivery pickup right now.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Counter Receipts -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
        <h3 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100">Today's Counter Receipts</h3>

        <div class="mt-4 divide-y divide-slate-100">
            @forelse($recentReceipts as $rcpt)
                <div class="py-3 flex items-center justify-between text-xs">
                    <div>
                        <span class="font-mono font-bold text-blue-600">{{ $rcpt->receipt_number }}</span>
                        <span class="font-bold text-slate-900 ml-2">{{ $rcpt->customer->name ?? 'Customer' }}</span>
                        <p class="text-slate-500 mt-0.5">Mode: <span class="uppercase font-semibold text-slate-700">{{ $rcpt->payment_mode }}</span> • Ref: {{ $rcpt->transaction_reference ?? 'Counter Cash' }}</p>
                    </div>
                    <div class="text-right">
                        <span class="font-mono font-black text-sm text-slate-900">₹{{ number_format($rcpt->amount, 2) }}</span>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 py-3">No payments collected yet today.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection

