@extends('layouts.app', ['title' => 'Executive Owner Dashboard', 'header' => 'Enterprise Executive Dashboard'])

@section('content')
<div class="space-y-8">

    <!-- KPI Summary Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Total Today Sales Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm relative overflow-hidden group hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Today's Total Sales</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 mt-1">₹{{ number_format($todaySales, 2) }}</h3>
                    <p class="text-[11px] text-emerald-600 font-semibold mt-1 flex items-center gap-1">
                        <span>This Month: ₹{{ number_format($monthSales, 2) }}</span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg border border-blue-100">
                    ₹
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
        </div>

        <!-- Orders in Lens Lab -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm relative overflow-hidden group hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Active Lab Jobs</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $ordersInLabCount }}</h3>
                    <p class="text-[11px] text-amber-600 font-semibold mt-1">Surfacing & Edging active</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 to-orange-500"></div>
        </div>

        <!-- Stock Valuation -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm relative overflow-hidden group hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Inventory Valuation</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 mt-1">₹{{ number_format($totalValuation, 2) }}</h3>
                    <p class="text-[11px] text-teal-600 font-semibold mt-1">Cost value in depots</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center border border-teal-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-teal-500 to-emerald-500"></div>
        </div>

        <!-- Total Customers Registered -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm relative overflow-hidden group hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Customers</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $totalCustomersCount }}</h3>
                    <p class="text-[11px] text-indigo-600 font-semibold mt-1">Registered patient base</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center border border-purple-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-purple-500 to-indigo-500"></div>
        </div>

    </div>

    <!-- Active Lens Lab Pipeline -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Active Lens Processing & Edging Board</h2>
                <p class="text-xs text-slate-500 mt-0.5">Surfacing, coating, edging, and quality control status</p>
            </div>
            <a href="{{ route('laboratory.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                <span>View Full Lab Kanban</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-6">Job #</th>
                        <th class="py-3 px-6">Customer & Order</th>
                        <th class="py-3 px-6">Optical Specification Snapshot</th>
                        <th class="py-3 px-6">Technician</th>
                        <th class="py-3 px-6">Current Stage</th>
                        <th class="py-3 px-6 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($activeLabJobs as $job)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 px-6 font-mono font-bold text-blue-600">{{ $job->job_number }}</td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900">{{ $job->salesOrder->customer->name ?? 'N/A' }}</div>
                                <div class="text-[11px] text-slate-500">Order: {{ $job->salesOrder->order_number ?? 'N/A' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-[11px] text-slate-700 font-mono">
                                    OD: {{ $job->optical_specifications['od']['sph'] ?? '0' }} / {{ $job->optical_specifications['od']['cyl'] ?? '0' }}
                                    | OS: {{ $job->optical_specifications['os']['sph'] ?? '0' }} / {{ $job->optical_specifications['os']['cyl'] ?? '0' }}
                                </div>
                                <div class="text-[10px] text-teal-600 font-medium mt-0.5">{{ $job->optical_specifications['lens'] ?? 'Custom Prescription' }}</div>
                            </td>
                            <td class="py-4 px-6 text-slate-700">{{ $job->technician->name ?? 'Unassigned' }}</td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider
                                    {{ $job->stage === 'edging' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ str_replace('_', ' ', $job->stage) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('laboratory.show', $job->id) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-700 text-xs font-semibold transition">Manage Job</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">No active lens laboratory orders.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Two-Column Grid: Recent Orders & Recent Prescriptions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Recent Sales Orders -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h2 class="text-base font-bold text-slate-900">Recent Sales Orders</h2>
                <a href="{{ route('orders.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">View All</a>
            </div>
            <div class="mt-4 divide-y divide-slate-100">
                @foreach($recentOrders as $order)
                    <div class="py-3.5 flex items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-sm text-slate-900">{{ $order->order_number }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                    {{ $order->order_status === 'delivered' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ str_replace('_', ' ', $order->order_status) }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $order->customer->name }} • {{ $order->items->count() }} item(s)</p>
                        </div>
                        <div class="text-right">
                            <div class="font-extrabold text-sm text-slate-900">₹{{ number_format($order->total_amount, 2) }}</div>
                            <div class="text-[11px] {{ $order->balance_amount > 0 ? 'text-rose-600 font-bold' : 'text-emerald-600' }}">
                                {{ $order->balance_amount > 0 ? 'Due: ₹'.number_format($order->balance_amount, 2) : 'Fully Paid' }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Prescriptions Recorded -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h2 class="text-base font-bold text-slate-900">Recent Optical Prescriptions</h2>
                <a href="{{ route('prescriptions.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">View All</a>
            </div>
            <div class="mt-4 divide-y divide-slate-100">
                @foreach($recentPrescriptions as $rx)
                    <div class="py-3.5 flex items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-sm text-slate-900">{{ $rx->customer->name }}</span>
                                <span class="text-[10px] font-mono text-slate-400">{{ $rx->prescription_number }}</span>
                            </div>
                            <p class="text-xs text-slate-600 font-mono mt-0.5">
                                OD: {{ $rx->od_sph ?? '0' }} SPH / {{ $rx->od_cyl ?? '0' }} CYL • OS: {{ $rx->os_sph ?? '0' }} SPH / {{ $rx->os_cyl ?? '0' }} CYL
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-1 rounded bg-teal-50 text-teal-700 text-xs font-semibold capitalize">{{ str_replace('_', ' ', $rx->prescription_type) }}</span>
                            <div class="text-[11px] text-slate-400 mt-1">{{ $rx->prescription_date->format('d M Y') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection
