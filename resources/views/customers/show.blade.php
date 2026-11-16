@extends('layouts.app', ['title' => $customer->name, 'header' => 'Customer Profile: ' . $customer->name])

@section('content')
<div class="space-y-8">

    <!-- Top Profile Summary Bar -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-600 text-white font-extrabold text-2xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                {{ substr($customer->name, 0, 1) }}
            </div>
            <div>
                <div class="flex items-center gap-2.5">
                    <h2 class="text-xl font-extrabold text-slate-900">{{ $customer->name }}</h2>
                    <span class="px-2 py-0.5 rounded text-[11px] font-mono bg-indigo-50 text-indigo-700 font-bold">{{ $customer->customer_code }}</span>
                </div>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-3">
                    <span>📞 {{ $customer->phone }}</span>
                    @if($customer->email)
                        <span>✉️ {{ $customer->email }}</span>
                    @endif
                    <span>📍 {{ $customer->city ?? 'Mumbai' }}</span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto">
            <a href="{{ route('prescriptions.create', ['customer_id' => $customer->id]) }}" class="flex-1 md:flex-none px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-600/30 transition text-center">
                + New Refraction / Rx
            </a>
            <a href="{{ route('pos.index') }}" class="flex-1 md:flex-none px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-md shadow-teal-600/30 transition text-center">
                + POS Sale
            </a>
        </div>
    </div>

    <!-- Prescriptions History -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900">Historical Eye Prescriptions</h3>
                <p class="text-xs text-slate-500 mt-0.5">Chronological record of optical examinations and prescription changes.</p>
            </div>
            <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold">{{ $customer->prescriptions->count() }} Prescriptions</span>
        </div>

        <div class="mt-4 divide-y divide-slate-100">
            @forelse($customer->prescriptions as $rx)
                <div class="py-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-mono font-bold text-blue-600 text-xs">{{ $rx->prescription_number }}</span>
                            <span class="text-xs text-slate-400">• {{ $rx->prescription_date->format('d M Y') }}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] bg-slate-100 text-slate-600 font-semibold capitalize">{{ str_replace('_', ' ', $rx->prescription_type) }}</span>
                        </div>
                        <div class="mt-2 grid grid-cols-2 gap-4 text-xs font-mono">
                            <div class="p-2 rounded bg-slate-50 border border-slate-100">
                                <span class="font-bold text-blue-600">OD:</span> {{ $rx->formatPower('OD') }}
                            </div>
                            <div class="p-2 rounded bg-slate-50 border border-slate-100">
                                <span class="font-bold text-teal-600">OS:</span> {{ $rx->formatPower('OS') }}
                            </div>
                        </div>
                        @if($rx->clinical_notes)
                            <p class="text-[11px] text-slate-500 mt-1.5 italic">{{ $rx->clinical_notes }}</p>
                        @endif
                    </div>

                    <a href="{{ route('prescriptions.show', $rx->id) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-700 text-xs font-bold transition">
                        View Rx Card →
                    </a>
                </div>
            @empty
                <div class="py-8 text-center text-slate-400 text-xs">No prescriptions recorded for this customer yet.</div>
            @endforelse
        </div>
    </div>

    <!-- Sales Orders History -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900">Spectacle & Contact Lens Purchases</h3>
                <p class="text-xs text-slate-500 mt-0.5">Purchased frames, lenses, delivery dates, and payment settlement records.</p>
            </div>
            <span class="px-2.5 py-1 rounded-full bg-teal-50 text-teal-700 text-xs font-bold">{{ $customer->salesOrders->count() }} Orders</span>
        </div>

        <div class="mt-4 divide-y divide-slate-100">
            @forelse($customer->salesOrders as $order)
                <div class="py-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-slate-900 text-sm">{{ $order->order_number }}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                {{ $order->order_status === 'delivered' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ str_replace('_', ' ', $order->order_status) }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">
                            Date: {{ $order->order_date->format('d M Y') }} • {{ $order->items->count() }} line item(s)
                        </p>
                    </div>

                    <div class="flex items-center gap-6">
                        <div class="text-right">
                            <div class="font-extrabold text-sm text-slate-900">₹{{ number_format($order->total_amount, 2) }}</div>
                            <div class="text-[11px] {{ $order->balance_amount > 0 ? 'text-rose-600 font-bold' : 'text-emerald-600' }}">
                                {{ $order->balance_amount > 0 ? 'Due: ₹'.number_format($order->balance_amount, 2) : 'Fully Paid' }}
                            </div>
                        </div>
                        <a href="{{ route('orders.show', $order->id) }}" class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-teal-50 hover:text-teal-700 text-slate-700 text-xs font-bold transition">
                            Details →
                        </a>
                    </div>
                </div>
            @empty
                <div class="py-8 text-center text-slate-400 text-xs">No orders recorded for this customer yet.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection

