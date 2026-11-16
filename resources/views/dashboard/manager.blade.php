@extends('layouts.app', ['title' => 'Store Manager Dashboard', 'header' => 'Store Operations & Branch Management'])

@section('content')
<div class="space-y-8">

    <!-- KPI Summary Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Today's Branch Sales</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1">₹{{ number_format($todaySales, 2) }}</h3>
                    <p class="text-[11px] text-emerald-600 font-semibold mt-1">Month: ₹{{ number_format($monthSales, 2) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">
                    ₹
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Orders in Lab</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $ordersInLabCount }}</h3>
                    <p class="text-[11px] text-amber-600 font-semibold mt-1">Surfacing & Edging</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg">
                    🔬
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 to-orange-500"></div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Ready for Fitting</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $readyForDeliveryCount }}</h3>
                    <p class="text-[11px] text-emerald-600 font-semibold mt-1">QC passed spectacles</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                    ✓
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-500"></div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Stock Reorder Alerts</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $lowStockProducts->count() }}</h3>
                    <p class="text-[11px] text-rose-600 font-semibold mt-1">Frames / Lenses below min</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-lg">
                    ⚠️
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-rose-500 to-red-500"></div>
        </div>

    </div>

    <!-- Active Orders Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-base font-bold text-slate-900">Branch Order Pipeline</h3>
            <a href="{{ route('orders.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">All Orders →</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6">Order #</th>
                        <th class="py-3.5 px-6">Customer</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Total</th>
                        <th class="py-3.5 px-6 text-right">Balance Due</th>
                        <th class="py-3.5 px-6 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($recentOrders as $ord)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 px-6 font-mono font-bold text-blue-600">{{ $ord->order_number }}</td>
                            <td class="py-4 px-6 font-bold text-slate-900">{{ $ord->customer->name }}</td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ $ord->order_status === 'delivered' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ str_replace('_', ' ', $ord->order_status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right font-mono font-bold text-slate-900">₹{{ number_format($ord->total_amount, 2) }}</td>
                            <td class="py-4 px-6 text-right font-mono {{ $ord->balance_amount > 0 ? 'text-rose-600 font-bold' : 'text-slate-400' }}">₹{{ number_format($ord->balance_amount, 2) }}</td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('orders.show', $ord->id) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-700 text-xs font-semibold transition">View Order</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
