@extends('layouts.app', ['title' => 'Customer CRM', 'header' => 'Customer CRM & Patient Profiles'])

@section('content')
<div class="space-y-6">

    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('customers.index') }}" class="relative w-full sm:w-96">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Search by name, phone, code, or GSTIN..." 
                class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition"
            >
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </form>

        <a href="{{ route('customers.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/30 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            <span>Register New Customer</span>
        </a>
    </div>

    <!-- Customers List -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6">Customer Code</th>
                        <th class="py-3.5 px-6">Name & Phone</th>
                        <th class="py-3.5 px-6">City & State</th>
                        <th class="py-3.5 px-6">Prescriptions</th>
                        <th class="py-3.5 px-6">Sales Orders</th>
                        <th class="py-3.5 px-6">Loyalty Points</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($customers as $c)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 px-6 font-mono font-bold text-indigo-600">{{ $c->customer_code }}</td>
                            <td class="py-4 px-6">
                                <a href="{{ route('customers.show', $c->id) }}" class="font-bold text-slate-900 hover:text-indigo-600 hover:underline text-sm">{{ $c->name }}</a>
                                <div class="text-[11px] text-slate-500">{{ $c->phone }} {{ $c->email ? '• ' . $c->email : '' }}</div>
                            </td>
                            <td class="py-4 px-6 text-slate-600">
                                {{ $c->city ?? 'Mumbai' }}, {{ $c->state ?? 'Maharashtra' }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 font-bold text-[11px]">
                                    {{ $c->prescriptions_count }} recorded
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full bg-teal-50 text-teal-700 font-bold text-[11px]">
                                    {{ $c->sales_orders_count }} orders
                                </span>
                            </td>
                            <td class="py-4 px-6 font-extrabold text-amber-600">
                                {{ $c->loyalty_points }} pts
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <a href="{{ route('prescriptions.create', ['customer_id' => $c->id]) }}" class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 text-xs font-semibold transition">+ Rx</a>
                                <a href="{{ route('customers.show', $c->id) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 text-xs font-semibold transition">Profile</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">No customer records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $customers->links() }}
        </div>
    </div>

</div>
@endsection

