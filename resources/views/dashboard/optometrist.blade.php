@extends('layouts.app', ['title' => 'Optometrist Clinical Dashboard', 'header' => 'Optometry Clinical & Refraction Dashboard'])

@section('content')
<div class="space-y-8">

    <!-- Top Action Banner -->
    <div class="bg-gradient-to-r from-blue-700 to-indigo-800 rounded-2xl p-6 text-white flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-lg shadow-blue-700/20">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full bg-blue-500/30 text-teal-300 text-xs font-semibold uppercase tracking-wider">Clinical Station</span>
                <span class="text-xs text-blue-200">Mumbai Flagship Eye Clinic</span>
            </div>
            <h2 class="text-xl font-black mt-1">Welcome, {{ $user->name }}</h2>
            <p class="text-xs text-blue-100 mt-0.5">Subjective & objective refraction bench, slit-lamp exams, and 3D progressive fitting heights.</p>
        </div>

        <a href="{{ route('prescriptions.create') }}" class="px-5 py-3 rounded-xl bg-teal-400 hover:bg-teal-300 text-slate-900 font-extrabold text-xs shadow-md transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>+ Start New Eye Refraction</span>
        </a>
    </div>

    <!-- Clinical KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Today's Refractions</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $todayPrescriptionsCount }}</h3>
                <p class="text-[11px] text-teal-600 font-medium mt-0.5">Completed eye exams today</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl">
                👁️
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Patient Prescriptions</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $totalPrescriptionsCount }}</h3>
                <p class="text-[11px] text-indigo-600 font-medium mt-0.5">Digitally signed & archived</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xl">
                📋
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Progressive Lenses</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $progressivePrescriptionsCount }}</h3>
                <p class="text-[11px] text-purple-600 font-medium mt-0.5">Multifocal fittings</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xl">
                👓
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Patients Pending Exam</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $patientsWaitingRefraction }}</h3>
                <p class="text-[11px] text-amber-600 font-medium mt-0.5">Walk-ins waiting for optometrist</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xl">
                ⏳
            </div>
        </div>

    </div>

    <!-- Recent Clinical Prescriptions Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900">Recent Clinical Prescriptions & Refractions</h3>
                <p class="text-xs text-slate-500 mt-0.5">Verified OD (Right Eye) & OS (Left Eye) optical measurements.</p>
            </div>
            <a href="{{ route('prescriptions.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">View All Prescriptions →</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6">Rx Number</th>
                        <th class="py-3.5 px-6">Patient Name</th>
                        <th class="py-3.5 px-6">OD (Right Eye)</th>
                        <th class="py-3.5 px-6">OS (Left Eye)</th>
                        <th class="py-3.5 px-6">Distance PD</th>
                        <th class="py-3.5 px-6">Prescription Type</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($recentPrescriptions as $rx)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 px-6 font-mono font-bold text-blue-600">
                                <a href="{{ route('prescriptions.show', $rx->id) }}" class="hover:underline">{{ $rx->prescription_number }}</a>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900">{{ $rx->customer->name ?? 'Patient' }}</div>
                                <div class="text-[11px] text-slate-500">📞 {{ $rx->customer->phone ?? '' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-1 rounded bg-blue-50 font-mono text-[11px] text-blue-800 font-semibold">
                                    {{ $rx->formatPower('OD') }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-1 rounded bg-teal-50 font-mono text-[11px] text-teal-800 font-semibold">
                                    {{ $rx->formatPower('OS') }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-mono font-bold text-slate-800">
                                {{ $rx->pd_distance ? $rx->pd_distance . ' mm' : 'N/A' }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700">
                                    {{ str_replace('_', ' ', $rx->prescription_type) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <a href="{{ route('prescriptions.show', $rx->id) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-700 text-xs font-semibold transition">View Card</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">No prescriptions recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
