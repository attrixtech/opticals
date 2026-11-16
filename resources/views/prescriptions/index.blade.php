@extends('layouts.app', ['title' => 'Optical Prescriptions', 'header' => 'Optical Prescriptions & Refractions'])

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('prescriptions.index') }}" class="relative w-full sm:w-96">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Search by customer name, phone, or Rx #..." 
                class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
            >
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </form>

        <a href="{{ route('prescriptions.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-600/30 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>New Eye Examination (OD/OS)</span>
        </a>
    </div>

    <!-- Prescriptions Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6">Rx Number</th>
                        <th class="py-3.5 px-6">Customer</th>
                        <th class="py-3.5 px-6">Right Eye (OD)</th>
                        <th class="py-3.5 px-6">Left Eye (OS)</th>
                        <th class="py-3.5 px-6">Type & PD</th>
                        <th class="py-3.5 px-6">Exam Date</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($prescriptions as $rx)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 px-6 font-mono font-bold text-blue-600">
                                <a href="{{ route('prescriptions.show', $rx->id) }}" class="hover:underline">{{ $rx->prescription_number }}</a>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900">{{ $rx->customer->name ?? 'N/A' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $rx->customer->phone ?? '' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-1 rounded bg-slate-100 font-mono text-[11px] text-slate-800 font-semibold">
                                    {{ $rx->formatPower('OD') }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-1 rounded bg-slate-100 font-mono text-[11px] text-slate-800 font-semibold">
                                    {{ $rx->formatPower('OS') }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="capitalize text-slate-900 font-bold">{{ str_replace('_', ' ', $rx->prescription_type) }}</div>
                                <div class="text-[11px] text-slate-500">PD: {{ $rx->pd_distance ? $rx->pd_distance . 'mm' : 'N/A' }}</div>
                            </td>
                            <td class="py-4 px-6 text-slate-600">
                                {{ $rx->prescription_date->format('d M Y') }}
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <a href="{{ route('prescriptions.show', $rx->id) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-700 text-xs font-semibold transition">View Card</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">No optical prescriptions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $prescriptions->links() }}
        </div>
    </div>

</div>
@endsection

