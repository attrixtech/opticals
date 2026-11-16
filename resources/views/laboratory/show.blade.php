@extends('layouts.app', ['title' => 'Lab Job #' . $job->job_number, 'header' => 'Lab Job Card: ' . $job->job_number])

@section('content')
<div class="space-y-8">

    <!-- Top Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('laboratory.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">← Back to Lab Pipeline</a>
        <div class="flex items-center gap-2">
            <span class="text-xs text-slate-500">Status:</span>
            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                {{ $job->stage === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                {{ str_replace('_', ' ', $job->stage) }}
            </span>
        </div>
    </div>

    <!-- Main Grid: Job Details & Pipeline Stage Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Optical Specifications & 8-Point QC -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Optical Snapshot Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900">Immutable Optical Job Specifications</h3>
                    <span class="text-xs font-mono text-slate-400">Order: {{ $job->salesOrder->order_number }}</span>
                </div>

                <!-- Parameters Table -->
                <div class="mt-4 border border-slate-200 rounded-xl overflow-hidden text-xs">
                    <table class="w-full text-center">
                        <thead class="bg-slate-50 text-slate-600 font-bold uppercase border-b border-slate-200">
                            <tr>
                                <th class="py-2.5 px-4 text-left">Eye Target</th>
                                <th class="py-2.5 px-3">Sphere</th>
                                <th class="py-2.5 px-3">Cylinder</th>
                                <th class="py-2.5 px-3">Axis</th>
                                <th class="py-2.5 px-3">Add</th>
                                <th class="py-2.5 px-3">Monocular PD</th>
                                <th class="py-2.5 px-3">Fitting Ht</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            <tr>
                                <td class="py-3 px-4 text-left font-bold text-blue-600 bg-blue-50/40">Right Eye (OD)</td>
                                <td class="py-3 px-3 font-mono font-bold">{{ $job->optical_specifications['od']['sph'] ?? '0.00' }}</td>
                                <td class="py-3 px-3 font-mono">{{ $job->optical_specifications['od']['cyl'] ?? '0.00' }}</td>
                                <td class="py-3 px-3 font-mono">{{ $job->optical_specifications['od']['axis'] ?? '-' }}°</td>
                                <td class="py-3 px-3 font-mono">{{ $job->optical_specifications['od']['add'] ?? '-' }}</td>
                                <td class="py-3 px-3">{{ $job->optical_specifications['measurements']['pd_right'] ?? ($job->optical_specifications['pd'] ? ($job->optical_specifications['pd'] / 2) . 'mm' : '31mm') }}</td>
                                <td class="py-3 px-3">{{ $job->optical_specifications['measurements']['fitting_height_r'] ?? '20mm' }}</td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 text-left font-bold text-teal-600 bg-teal-50/40">Left Eye (OS)</td>
                                <td class="py-3 px-3 font-mono font-bold">{{ $job->optical_specifications['os']['sph'] ?? '0.00' }}</td>
                                <td class="py-3 px-3 font-mono">{{ $job->optical_specifications['os']['cyl'] ?? '0.00' }}</td>
                                <td class="py-3 px-3 font-mono">{{ $job->optical_specifications['os']['axis'] ?? '-' }}°</td>
                                <td class="py-3 px-3 font-mono">{{ $job->optical_specifications['os']['add'] ?? '-' }}</td>
                                <td class="py-3 px-3">{{ $job->optical_specifications['measurements']['pd_left'] ?? ($job->optical_specifications['pd'] ? ($job->optical_specifications['pd'] / 2) . 'mm' : '31mm') }}</td>
                                <td class="py-3 px-3">{{ $job->optical_specifications['measurements']['fitting_height_l'] ?? '20mm' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 p-3 bg-slate-50 rounded-xl text-xs space-y-1">
                    <p><span class="font-bold text-slate-700">Lens Profile:</span> {{ $job->optical_specifications['lens'] ?? 'Custom Prescription Lenses' }}</p>
                    <p><span class="font-bold text-slate-700">Frame Model:</span> {{ $job->optical_specifications['frame'] ?? 'Standard Full Rim' }}</p>
                </div>
            </div>

            <!-- 8-Point Optical Quality Control Inspection Form -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900">8-Point Optical Quality Control (QC) Sign-Off</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Verification against ANSI Z80.1 / ISO optical tolerances before customer delivery.</p>
                    </div>
                    @if($job->qualityControl)
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase {{ $job->qualityControl->qc_status === 'passed' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                            QC {{ ucfirst($job->qualityControl->qc_status) }}
                        </span>
                    @endif
                </div>

                <form method="POST" action="{{ route('laboratory.qc', $job->id) }}" class="mt-5 space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer">
                            <input type="checkbox" name="check_lens_power_sph_cyl_axis" value="1" {{ ($job->qualityControl && $job->qualityControl->check_lens_power_sph_cyl_axis) ? 'checked' : 'checked' }} class="w-4 h-4 text-blue-600 rounded">
                            <span class="font-semibold text-slate-800">1. SPH / CYL / Axis Power Verified</span>
                        </label>

                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer">
                            <input type="checkbox" name="check_optical_center_pd" value="1" {{ ($job->qualityControl && $job->qualityControl->check_optical_center_pd) ? 'checked' : 'checked' }} class="w-4 h-4 text-blue-600 rounded">
                            <span class="font-semibold text-slate-800">2. Optical Center & PD Aligned</span>
                        </label>

                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer">
                            <input type="checkbox" name="check_fitting_height" value="1" {{ ($job->qualityControl && $job->qualityControl->check_fitting_height) ? 'checked' : 'checked' }} class="w-4 h-4 text-blue-600 rounded">
                            <span class="font-semibold text-slate-800">3. Fitting / Segment Height Verified</span>
                        </label>

                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer">
                            <input type="checkbox" name="check_lens_edging_bevel_fit" value="1" {{ ($job->qualityControl && $job->qualityControl->check_lens_edging_bevel_fit) ? 'checked' : 'checked' }} class="w-4 h-4 text-blue-600 rounded">
                            <span class="font-semibold text-slate-800">4. Edging Bevel & Groove Fit Snug</span>
                        </label>

                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer">
                            <input type="checkbox" name="check_lens_surface_scratches_coating" value="1" {{ ($job->qualityControl && $job->qualityControl->check_lens_surface_scratches_coating) ? 'checked' : 'checked' }} class="w-4 h-4 text-blue-600 rounded">
                            <span class="font-semibold text-slate-800">5. Surface Clean (Zero Scratches/Crazing)</span>
                        </label>

                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer">
                            <input type="checkbox" name="check_frame_four_point_alignment" value="1" {{ ($job->qualityControl && $job->qualityControl->check_frame_four_point_alignment) ? 'checked' : 'checked' }} class="w-4 h-4 text-blue-600 rounded">
                            <span class="font-semibold text-slate-800">6. Frame 4-Point Bench Alignment</span>
                        </label>

                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer">
                            <input type="checkbox" name="check_screws_and_lens_security" value="1" {{ ($job->qualityControl && $job->qualityControl->check_screws_and_lens_security) ? 'checked' : 'checked' }} class="w-4 h-4 text-blue-600 rounded">
                            <span class="font-semibold text-slate-800">7. Screws Tight & Lens Firm</span>
                        </label>

                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer">
                            <input type="checkbox" name="check_cleanliness_and_presentation" value="1" {{ ($job->qualityControl && $job->qualityControl->check_cleanliness_and_presentation) ? 'checked' : 'checked' }} class="w-4 h-4 text-blue-600 rounded">
                            <span class="font-semibold text-slate-800">8. Cleanliness & Case Packaging</span>
                        </label>
                    </div>

                    <div class="pt-3">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">QC Decision</label>
                        <div class="flex items-center gap-4">
                            <label class="flex items-center gap-2 text-xs font-bold text-emerald-700">
                                <input type="radio" name="qc_status" value="passed" checked class="text-emerald-600">
                                <span>Passed & Ready for Delivery</span>
                            </label>
                            <label class="flex items-center gap-2 text-xs font-bold text-rose-700">
                                <input type="radio" name="qc_status" value="rejected" class="text-rose-600">
                                <span>Failed (Send to Rework)</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <input type="text" name="rejection_reason" placeholder="Notes / Rejection reason if failed..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold shadow-md shadow-purple-600/30 transition">
                            Submit Quality Inspection Decision
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <!-- Right Col: Stage Pipeline Advancer & History Logs -->
        <div class="space-y-6">
            
            <!-- Stage Transition Actions -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 pb-3 border-b border-slate-100">Advance Processing Stage</h3>
                
                <form method="POST" action="{{ route('laboratory.stage', $job->id) }}" class="mt-4 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Transition To:</label>
                        <select name="stage" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800">
                            <option value="lens_picked" {{ $job->stage === 'lens_picked' ? 'selected' : '' }}>1. Lens Blank Picked from Stock</option>
                            <option value="surfacing" {{ $job->stage === 'surfacing' ? 'selected' : '' }}>2. Blocking & CNC Surfacing</option>
                            <option value="coating" {{ $job->stage === 'coating' ? 'selected' : '' }}>3. Hard Coat & AR Coating</option>
                            <option value="edging" {{ $job->stage === 'edging' ? 'selected' : '' }}>4. Patternless Edging & Bevel</option>
                            <option value="frame_mounting" {{ $job->stage === 'frame_mounting' ? 'selected' : '' }}>5. Frame Mounting</option>
                            <option value="qc_pending" {{ $job->stage === 'qc_pending' ? 'selected' : '' }}>6. QC Inspection Pending</option>
                            <option value="completed" {{ $job->stage === 'completed' ? 'selected' : '' }}>7. Completed & Dispatched</option>
                            <option value="rework" {{ $job->stage === 'rework' ? 'selected' : '' }}>Rework Required</option>
                        </select>
                    </div>

                    <div>
                        <input type="text" name="remarks" placeholder="Technician operation remarks..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition">
                        Update Pipeline Stage
                    </button>
                </form>
            </div>

            <!-- Job History Audit Logs -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 pb-3 border-b border-slate-100">Lab Processing Trail</h3>
                
                <div class="mt-4 space-y-3">
                    @forelse($job->logs as $log)
                        <div class="p-3 bg-slate-50 rounded-xl text-xs border border-slate-100">
                            <div class="flex items-center justify-between font-bold text-slate-800">
                                <span>{{ str_replace('_', ' ', $log->to_stage) }}</span>
                                <span class="text-[10px] text-slate-400">{{ $log->created_at->format('d M, H:i') }}</span>
                            </div>
                            @if($log->remarks)
                                <p class="text-[11px] text-slate-500 mt-1">{{ $log->remarks }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs text-slate-400">No previous stage logs recorded.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

