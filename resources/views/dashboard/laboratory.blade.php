@extends('layouts.app', ['title' => 'Laboratory Workbench', 'header' => 'Lens Surfacing & Edging Laboratory Workbench'])

@section('content')
<div class="space-y-8">

    <!-- Top Status Banner -->
    <div class="bg-gradient-to-r from-slate-900 to-amber-950 rounded-2xl p-6 text-white flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-lg">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full bg-amber-500/30 text-amber-300 text-xs font-semibold uppercase tracking-wider">Lab Station</span>
                <span class="text-xs text-slate-300">CNC Surfacing & 3D Patternless Edger</span>
            </div>
            <h2 class="text-xl font-black mt-1">Technician Workbench: {{ $user->name }}</h2>
            <p class="text-xs text-slate-300 mt-0.5">Manage blank lens picking, surfacing, hard/AR coating, edging, and 8-point optical quality inspection.</p>
        </div>

        <a href="{{ route('laboratory.index') }}" class="px-5 py-3 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs shadow-md transition flex items-center gap-2">
            <span>View Kanban Board →</span>
        </a>
    </div>

    <!-- Lab Pipeline Stage Metrics -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">1. Surfacing</span>
            <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $jobsInSurfacing }}</h3>
            <p class="text-[10px] text-slate-400 mt-0.5">CNC generating</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600">2. Edging & Mounting</span>
            <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $jobsInEdging }}</h3>
            <p class="text-[10px] text-amber-600 mt-0.5">Bevel & drill groove</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-[11px] font-bold uppercase tracking-wider text-purple-600">3. QC Pending</span>
            <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $jobsQcPending }}</h3>
            <p class="text-[10px] text-purple-600 mt-0.5">8-Point check</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">4. Dispatched</span>
            <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $jobsCompleted }}</h3>
            <p class="text-[10px] text-emerald-600 mt-0.5">To showroom</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-[11px] font-bold uppercase tracking-wider text-rose-600">Rework Jobs</span>
            <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $jobsRework }}</h3>
            <p class="text-[10px] text-rose-600 mt-0.5">QC rejection remakes</p>
        </div>

    </div>

    <!-- Active Jobs in Queue -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900">Active Lab Jobs on Workbench</h3>
                <p class="text-xs text-slate-500 mt-0.5">Jobs currently undergoing lens blocking, generator surfacing, AR coating, or bevel edging.</p>
            </div>
            <a href="{{ route('laboratory.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">Full Kanban →</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6">Job Number</th>
                        <th class="py-3.5 px-6">Patient & Order</th>
                        <th class="py-3.5 px-6">Optical Specs (OD / OS)</th>
                        <th class="py-3.5 px-6">Priority</th>
                        <th class="py-3.5 px-6">Current Stage</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($activeLabJobs as $job)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 px-6 font-mono font-bold text-amber-600">
                                <a href="{{ route('laboratory.show', $job->id) }}" class="hover:underline">{{ $job->job_number }}</a>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900">{{ $job->salesOrder->customer->name ?? 'Patient' }}</div>
                                <div class="text-[11px] text-slate-500">Order: {{ $job->salesOrder->order_number }}</div>
                            </td>
                            <td class="py-4 px-6 font-mono text-[11px]">
                                <span class="text-blue-700 font-bold">OD: {{ $job->optical_specifications['od']['sph'] ?? '0' }}/{{ $job->optical_specifications['od']['cyl'] ?? '0' }}</span>
                                <span class="text-slate-300 mx-1">|</span>
                                <span class="text-teal-700 font-bold">OS: {{ $job->optical_specifications['os']['sph'] ?? '0' }}/{{ $job->optical_specifications['os']['cyl'] ?? '0' }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                    {{ $job->priority === 'urgent' ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $job->priority }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ $job->stage === 'edging' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ str_replace('_', ' ', $job->stage) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('laboratory.show', $job->id) }}" class="px-3.5 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-600 hover:text-white text-amber-700 text-xs font-bold transition">
                                    Open Bench Card →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">All lab jobs are completed! No jobs in queue.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Frame Repairs on Bench Section -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Spectacle Frame Repairs Bench</h3>
                <p class="text-xs text-slate-500 mt-0.5">Frame alignments, hinge barrel replacements, screw threads, and soldering.</p>
            </div>
            <a href="{{ route('repairs.index') }}" class="text-xs font-bold text-orange-600 hover:text-orange-700">View Repairs →</a>
        </div>

        <div class="mt-4 divide-y divide-slate-100">
            @forelse($pendingRepairs as $rep)
                <div class="py-3 flex items-center justify-between text-xs">
                    <div>
                        <span class="font-mono font-bold text-orange-600">{{ $rep->repair_number }}</span>
                        <span class="font-bold text-slate-900 ml-2">{{ $rep->frame_description }}</span>
                        <p class="text-slate-500 mt-0.5">Defect: {{ $rep->issue_description }} (Customer: {{ $rep->customer->name ?? 'Walk-In' }})</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-800">
                        {{ $rep->status }}
                    </span>
                </div>
            @empty
                <p class="text-xs text-slate-400 py-3">No pending repairs on bench.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection
