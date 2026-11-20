@extends('layouts.app', ['title' => 'Prescription Details', 'header' => 'Prescription Card #' . $prescription->prescription_number])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Action Bar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('prescriptions.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">← Back to Prescriptions</a>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Print Prescription</span>
            </button>
            <a href="{{ route('pos.index') }}" class="px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-md shadow-teal-600/30 transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                <span>Create Order With This Rx</span>
            </a>
        </div>
    </div>

    <!-- Official Prescription Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-8 print:border-none print:shadow-none">
        
        <!-- Header Info -->
        <div class="flex items-start justify-between pb-6 border-b border-slate-100">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                    <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">thrumble.opticals Optometry Clinic</h2>
                </div>
                <p class="text-xs text-slate-500 mt-1">Lower Parel Flagship • Reg: 27AAACV1234F1Z5</p>
            </div>
            <div class="text-right">
                <div class="font-mono font-bold text-sm text-blue-600">{{ $prescription->prescription_number }}</div>
                <div class="text-xs text-slate-400 mt-0.5">Date: {{ $prescription->prescription_date->format('d M Y') }}</div>
            </div>
        </div>

        <!-- Patient Demographics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 py-5 border-b border-slate-100 text-xs">
            <div>
                <span class="text-slate-400 uppercase font-semibold text-[10px]">Patient Name</span>
                <p class="font-bold text-slate-900 text-sm mt-0.5">{{ $prescription->customer->name }}</p>
            </div>
            <div>
                <span class="text-slate-400 uppercase font-semibold text-[10px]">Contact Phone</span>
                <p class="font-semibold text-slate-800 mt-0.5">{{ $prescription->customer->phone }}</p>
            </div>
            <div>
                <span class="text-slate-400 uppercase font-semibold text-[10px]">Examined By</span>
                <p class="font-semibold text-slate-800 mt-0.5">{{ $prescription->doctor_name ?? 'Optometrist' }}</p>
            </div>
            <div>
                <span class="text-slate-400 uppercase font-semibold text-[10px]">Prescription Type</span>
                <p class="font-semibold text-teal-600 capitalize mt-0.5">{{ str_replace('_', ' ', $prescription->prescription_type) }}</p>
            </div>
        </div>

        <!-- Refraction Data Table -->
        <div class="py-6">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-3">Refraction Measurements</h3>
            <div class="border border-slate-200 rounded-xl overflow-hidden">
                <table class="w-full text-center text-xs">
                    <thead class="bg-slate-50 text-slate-600 uppercase font-bold border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4 text-left">Eye</th>
                            <th class="py-3 px-3">Sphere (SPH)</th>
                            <th class="py-3 px-3">Cylinder (CYL)</th>
                            <th class="py-3 px-3">Axis</th>
                            <th class="py-3 px-3">Add</th>
                            <th class="py-3 px-3">Prism</th>
                            <th class="py-3 px-3">Dist VA</th>
                            <th class="py-3 px-3">Near VA</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        <!-- OD -->
                        <tr>
                            <td class="py-3.5 px-4 text-left font-bold text-blue-600 bg-blue-50/40">Right Eye (OD)</td>
                            <td class="py-3.5 px-3 font-mono font-bold">{{ $prescription->od_sph !== null ? ($prescription->od_sph > 0 ? '+'.$prescription->od_sph : $prescription->od_sph) : '0.00' }}</td>
                            <td class="py-3.5 px-3 font-mono">{{ $prescription->od_cyl !== null ? ($prescription->od_cyl > 0 ? '+'.$prescription->od_cyl : $prescription->od_cyl) : '0.00' }}</td>
                            <td class="py-3.5 px-3 font-mono">{{ $prescription->od_axis !== null ? $prescription->od_axis.'°' : '-' }}</td>
                            <td class="py-3.5 px-3 font-mono">{{ $prescription->od_add ? '+'.$prescription->od_add : '-' }}</td>
                            <td class="py-3.5 px-3 font-mono">{{ $prescription->od_prism ? $prescription->od_prism.' '.$prescription->od_base : '-' }}</td>
                            <td class="py-3.5 px-3">{{ $prescription->od_va_distance ?? '6/6' }}</td>
                            <td class="py-3.5 px-3">{{ $prescription->od_va_near ?? 'N6' }}</td>
                        </tr>

                        <!-- OS -->
                        <tr>
                            <td class="py-3.5 px-4 text-left font-bold text-teal-600 bg-teal-50/40">Left Eye (OS)</td>
                            <td class="py-3.5 px-3 font-mono font-bold">{{ $prescription->os_sph !== null ? ($prescription->os_sph > 0 ? '+'.$prescription->os_sph : $prescription->os_sph) : '0.00' }}</td>
                            <td class="py-3.5 px-3 font-mono">{{ $prescription->os_cyl !== null ? ($prescription->os_cyl > 0 ? '+'.$prescription->os_cyl : $prescription->os_cyl) : '0.00' }}</td>
                            <td class="py-3.5 px-3 font-mono">{{ $prescription->os_axis !== null ? $prescription->os_axis.'°' : '-' }}</td>
                            <td class="py-3.5 px-3 font-mono">{{ $prescription->os_add ? '+'.$prescription->os_add : '-' }}</td>
                            <td class="py-3.5 px-3 font-mono">{{ $prescription->os_prism ? $prescription->os_prism.' '.$prescription->os_base : '-' }}</td>
                            <td class="py-3.5 px-3">{{ $prescription->os_va_distance ?? '6/6' }}</td>
                            <td class="py-3.5 px-3">{{ $prescription->os_va_near ?? 'N6' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pupillary Distance & Transposition Calculation -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100 text-xs">
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1.5">
                <span class="font-bold uppercase tracking-wider text-slate-700 block text-[11px]">Pupillary Measurements</span>
                <p class="text-slate-600">Binocular Distance PD: <span class="font-bold text-slate-900">{{ $prescription->pd_distance ? $prescription->pd_distance . ' mm' : 'Not recorded' }}</span></p>
                <p class="text-slate-600">Near PD: <span class="font-bold text-slate-900">{{ $prescription->pd_near ? $prescription->pd_near . ' mm' : 'Not recorded' }}</span></p>
            </div>

            <!-- Optical Transposition Helper (ANSI/ISO format) -->
            <div class="p-4 rounded-xl bg-blue-50/60 border border-blue-100 space-y-1 text-[11px]">
                <span class="font-bold uppercase tracking-wider text-blue-900 block">Optical Transposition (Minus Cyl Equivalent)</span>
                @if($odTransposed)
                    <p class="text-blue-800">OD: {{ $odTransposed['sph'] }} SPH / {{ $odTransposed['cyl'] }} CYL x {{ $odTransposed['axis'] }}°</p>
                @endif
                @if($osTransposed)
                    <p class="text-blue-800">OS: {{ $osTransposed['sph'] }} SPH / {{ $osTransposed['cyl'] }} CYL x {{ $osTransposed['axis'] }}°</p>
                @endif
                @if(!$odTransposed && !$osTransposed)
                    <p class="text-blue-600">No astigmatic cylinder present.</p>
                @endif
            </div>
        </div>

        @if($prescription->clinical_notes)
            <div class="mt-6 p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                <span class="font-bold text-slate-700 uppercase tracking-wider text-[10px] block mb-1">Doctor's Clinical Notes</span>
                <p class="text-slate-600">{{ $prescription->clinical_notes }}</p>
            </div>
        @endif

        <!-- Doctor Signature Footer -->
        <div class="mt-10 pt-6 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <div>
                <p class="font-semibold text-slate-800">Digitally Verified & Locked</p>
                <p class="text-[11px]">Prescription records are immutable per clinical audit standards.</p>
            </div>
            <div class="text-right">
                <div class="font-bold text-slate-900 border-b border-slate-300 pb-1 mb-1">{{ $prescription->doctor_name ?? 'Dr. Ananya Deshmukh' }}</div>
                <div class="text-[10px] uppercase tracking-wider">Authorized Optometrist</div>
            </div>
        </div>

    </div>

</div>
@endsection

