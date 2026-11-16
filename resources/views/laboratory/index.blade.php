@extends('layouts.app', ['title' => 'Lens Laboratory Management', 'header' => 'Lens Processing & Edging Laboratory'])

@section('content')
<div class="space-y-6">

    <!-- Pipeline Stage Filter Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2">
        <a href="{{ route('laboratory.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-bold transition {{ !$stage ? 'bg-slate-900 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            All Jobs ({{ $counts['total'] }})
        </a>
        <a href="{{ route('laboratory.index', ['stage' => 'job_created']) }}" class="px-4 py-2.5 rounded-xl text-xs font-bold transition {{ $stage === 'job_created' ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Job Created ({{ $counts['job_created'] }})
        </a>
        <a href="{{ route('laboratory.index', ['stage' => 'surfacing']) }}" class="px-4 py-2.5 rounded-xl text-xs font-bold transition {{ $stage === 'surfacing' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Surfacing & Coating ({{ $counts['surfacing'] }})
        </a>
        <a href="{{ route('laboratory.index', ['stage' => 'edging']) }}" class="px-4 py-2.5 rounded-xl text-xs font-bold transition {{ $stage === 'edging' ? 'bg-amber-600 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Edging & Mounting ({{ $counts['edging'] }})
        </a>
        <a href="{{ route('laboratory.index', ['stage' => 'qc_pending']) }}" class="px-4 py-2.5 rounded-xl text-xs font-bold transition {{ $stage === 'qc_pending' ? 'bg-purple-600 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            QC Pending ({{ $counts['qc_pending'] }})
        </a>
        <a href="{{ route('laboratory.index', ['stage' => 'completed']) }}" class="px-4 py-2.5 rounded-xl text-xs font-bold transition {{ $stage === 'completed' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Completed ({{ $counts['completed'] }})
        </a>
    </div>

    <!-- Job Cards Grid / List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($jobs as $job)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <!-- Header -->
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <span class="font-mono font-extrabold text-blue-600 text-sm">{{ $job->job_number }}</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider
                            {{ $job->priority === 'urgent' ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-600' }}">
                            {{ $job->priority }}
                        </span>
                    </div>

                    <!-- Customer & Order Link -->
                    <div class="mt-3">
                        <h4 class="font-bold text-slate-900 text-base">{{ $job->salesOrder->customer->name ?? 'Customer' }}</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Sales Order: <span class="font-semibold text-slate-700">{{ $job->salesOrder->order_number ?? 'N/A' }}</span></p>
                    </div>

                    <!-- Optical Specification Snapshot -->
                    <div class="mt-4 p-3 bg-slate-50 rounded-xl border border-slate-100 text-xs font-mono space-y-1">
                        <div class="text-slate-800 font-bold">
                            OD: {{ $job->optical_specifications['od']['sph'] ?? '0' }} / {{ $job->optical_specifications['od']['cyl'] ?? '0' }} x {{ $job->optical_specifications['od']['axis'] ?? '-' }}°
                        </div>
                        <div class="text-slate-800 font-bold">
                            OS: {{ $job->optical_specifications['os']['sph'] ?? '0' }} / {{ $job->optical_specifications['os']['cyl'] ?? '0' }} x {{ $job->optical_specifications['os']['axis'] ?? '-' }}°
                        </div>
                        <div class="text-[11px] text-teal-700 font-sans font-medium pt-1">
                            {{ $job->optical_specifications['lens'] ?? 'Custom Prescription' }}
                        </div>
                    </div>

                    <!-- Current Stage Badge -->
                    <div class="mt-4 flex items-center justify-between text-xs">
                        <span class="text-slate-500">Stage:</span>
                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider
                            {{ $job->stage === 'completed' ? 'bg-emerald-100 text-emerald-800' : ($job->stage === 'rework' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                            {{ str_replace('_', ' ', $job->stage) }}
                        </span>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-400">Tech: {{ $job->technician->name ?? 'Unassigned' }}</span>
                    <a href="{{ route('laboratory.show', $job->id) }}" class="px-3.5 py-1.5 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white text-xs font-bold transition">
                        Open Job Card →
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white p-12 rounded-2xl border border-slate-200 text-center text-slate-400">
                No laboratory job cards currently in this stage.
            </div>
        @endforelse
    </div>

</div>
@endsection

