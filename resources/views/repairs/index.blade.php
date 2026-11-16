@extends('layouts.app', ['title' => 'Repairs & Warranties', 'header' => 'Optical Frame Repairs & Warranty Service'])

@section('content')
<div class="space-y-8" x-data="{ openRepairModal: false }">

    <!-- Top Action Bar -->
    <div class="flex items-center justify-between bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <h2 class="text-base font-bold text-slate-900">Spectacle Repairs & Warranty Claims</h2>
            <p class="text-xs text-slate-500 mt-0.5">Frame alignments, hinge solders, screw replacements, and lens coating warranties.</p>
        </div>

        <button @click="openRepairModal = true" class="px-4 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold shadow-md shadow-orange-600/30 transition flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Intake Frame Repair Job</span>
        </button>
    </div>

    <!-- Frame Repairs Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900">Active Repair Bench Jobs</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6">Repair #</th>
                        <th class="py-3.5 px-6">Customer</th>
                        <th class="py-3.5 px-6">Frame & Issue Description</th>
                        <th class="py-3.5 px-6">Technician</th>
                        <th class="py-3.5 px-6 text-right">Est. Cost</th>
                        <th class="py-3.5 px-6 text-center">Status</th>
                        <th class="py-3.5 px-6 text-right">Update Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($repairs as $rep)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 px-6 font-mono font-bold text-orange-600">{{ $rep->repair_number }}</td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900">{{ $rep->customer->name ?? 'Walk-In' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $rep->customer->phone ?? '' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900">{{ $rep->frame_description }}</div>
                                <div class="text-[11px] text-slate-500">{{ $rep->issue_description }}</div>
                            </td>
                            <td class="py-4 px-6 text-slate-600">{{ $rep->technician->name ?? 'Bench Technician' }}</td>
                            <td class="py-4 px-6 text-right font-mono font-bold">₹{{ number_format($rep->estimated_cost, 2) }}</td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ $rep->status === 'delivered' ? 'bg-emerald-100 text-emerald-800' : ($rep->status === 'ready' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ str_replace('_', ' ', $rep->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <form method="POST" action="{{ route('repairs.status', $rep->id) }}" class="inline-flex items-center gap-1">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="p-1 bg-slate-50 border border-slate-200 rounded text-[11px] font-semibold">
                                        <option value="received" {{ $rep->status === 'received' ? 'selected' : '' }}>Received</option>
                                        <option value="in_repair" {{ $rep->status === 'in_repair' ? 'selected' : '' }}>In Repair</option>
                                        <option value="ready" {{ $rep->status === 'ready' ? 'selected' : '' }}>Ready</option>
                                        <option value="delivered" {{ $rep->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">No active frame repairs on bench.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Active Customer Warranties Section -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 pb-3 border-b border-slate-100">Active Lens & Frame Warranties</h3>

        <div class="mt-4 divide-y divide-slate-100">
            @forelse($warranties as $war)
                <div class="py-3 flex items-center justify-between text-xs">
                    <div>
                        <span class="font-mono font-bold text-teal-600">{{ $war->warranty_number }}</span>
                        <span class="font-bold text-slate-900 ml-2">{{ $war->customer->name ?? 'Customer' }}</span>
                        <p class="text-slate-500 mt-0.5">{{ $war->product_type }} • Valid: {{ $war->start_date->format('d M Y') }} to {{ $war->end_date->format('d M Y') }}</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800">
                        Active
                    </span>
                </div>
            @empty
                <p class="text-xs text-slate-400 py-4">No warranty cards recorded.</p>
            @endforelse
        </div>
    </div>

    <!-- Repair Intake Modal -->
    <div x-show="openRepairModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="openRepairModal = false" class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Intake Spectacle Repair</h3>
                <button @click="openRepairModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form method="POST" action="{{ route('repairs.store') }}" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Customer *</label>
                    <select name="customer_id" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Frame Description *</label>
                    <input type="text" name="frame_description" required placeholder="e.g. Ray-Ban Wayfarer Black Acetate" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Issue / Defect Description *</label>
                    <textarea name="issue_description" required rows="2" placeholder="e.g. Left hinge barrel screw broken inside frame. Align nose pads." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Estimated Cost (₹) *</label>
                        <input type="number" step="0.01" name="estimated_cost" value="350.00" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Advance Paid (₹)</label>
                        <input type="number" step="0.01" name="advance_paid" value="0.00" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                    </div>
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-700 mb-1">Assign Technician</label>
                    <select name="technician_id" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl">
                        @foreach($technicians as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="openRepairModal = false" class="px-4 py-2 border border-slate-200 rounded-xl text-slate-600 font-bold">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-orange-600 text-white rounded-xl font-bold shadow-md shadow-orange-600/30">Create Repair Job</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

