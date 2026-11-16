@extends('layouts.app', ['title' => 'Sales Orders', 'header' => 'Optical Sales Orders'])

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar & Status Filter -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('orders.index') }}" class="relative w-full sm:w-96">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Search order number or customer name..." 
                class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
            >
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </form>

        <a href="{{ route('pos.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-md shadow-teal-600/30 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Create New Order (POS)</span>
        </a>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6">Order #</th>
                        <th class="py-3.5 px-6">Customer</th>
                        <th class="py-3.5 px-6">Order Date</th>
                        <th class="py-3.5 px-6">Items Ordered</th>
                        <th class="py-3.5 px-6">Lifecycle Status</th>
                        <th class="py-3.5 px-6">Total Amount</th>
                        <th class="py-3.5 px-6">Payment Balance</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($orders as $o)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 px-6 font-mono font-bold text-blue-600">
                                <a href="{{ route('orders.show', $o->id) }}" class="hover:underline">{{ $o->order_number }}</a>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900">{{ $o->customer->name ?? 'Walk-In Customer' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $o->customer->phone ?? '' }}</div>
                            </td>
                            <td class="py-4 px-6 text-slate-600">
                                {{ $o->order_date->format('d M Y') }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-1 rounded bg-slate-100 font-bold text-slate-700">
                                    {{ $o->items->count() }} item(s)
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ $o->order_status === 'delivered' ? 'bg-emerald-100 text-emerald-800' : ($o->order_status === 'in_lab' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ str_replace('_', ' ', $o->order_status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-extrabold text-slate-900 text-sm">
                                ₹{{ number_format($o->total_amount, 2) }}
                            </td>
                            <td class="py-4 px-6">
                                @if($o->balance_amount > 0)
                                    <span class="text-rose-600 font-bold">Due: ₹{{ number_format($o->balance_amount, 2) }}</span>
                                @else
                                    <span class="text-emerald-600 font-bold">Paid</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <a href="{{ route('orders.show', $o->id) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-700 text-xs font-semibold transition">View Order</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">No optical sales orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $orders->links() }}
        </div>
    </div>

</div>
@endsection

