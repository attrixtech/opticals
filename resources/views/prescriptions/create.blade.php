@extends('layouts.app', ['title' => 'New Optical Prescription', 'header' => 'New Optical Prescription Entry'])

@section('content')
<div class="max-w-5xl mx-auto bg-white rounded-2xl border border-slate-200/80 shadow-sm p-8">

    <div class="mb-6 pb-5 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Clinical Refraction & Prescription Form</h2>
            <p class="text-xs text-slate-500 mt-0.5">Capture independent OD (Right) and OS (Left) sphere, cylinder, axis, addition, and pupillary measurements.</p>
        </div>
        <a href="{{ route('prescriptions.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">← Back to List</a>
    </div>

    <form method="POST" action="{{ route('prescriptions.store') }}" class="space-y-8">
        @csrf

        <!-- Patient & Optometrist Information -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Customer / Patient *</label>
                <select name="customer_id" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                    <option value="">-- Choose Patient --</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}" {{ (isset($selectedCustomer) && $selectedCustomer->id == $c->id) ? 'selected' : '' }}>
                            {{ $c->name }} ({{ $c->phone }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Examination Date *</label>
                <input type="date" name="prescription_date" value="{{ date('Y-m-d') }}" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Prescription Type *</label>
                <select name="prescription_type" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                    <option value="single_vision">Single Vision</option>
                    <option value="progressive">Progressive (Multifocal)</option>
                    <option value="bifocal">Bifocal (Kryptok / D-Seg)</option>
                    <option value="reading">Reading Vision</option>
                    <option value="computer">Computer / Occupational</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Examining Doctor / Optometrist</label>
                <input type="text" name="doctor_name" value="Dr. Ananya Deshmukh, M.Optom" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Clinic / Hospital Name</label>
                <input type="text" name="clinic_hospital" value="thrumble.opticals Optometry Clinic" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Validity Expiry Date</label>
                <input type="date" name="expiry_date" value="{{ date('Y-m-d', strtotime('+1 year')) }}" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
            </div>
        </div>

        <!-- Optometric Refraction Grid (OD & OS) -->
        <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="bg-slate-900 text-white px-6 py-3 flex items-center justify-between text-xs font-bold uppercase tracking-wider">
                <span>Standard Optical Refraction Table</span>
                <span class="text-teal-400">0.25 D Steps</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-center text-xs">
                    <thead class="bg-slate-50 text-slate-700 font-bold uppercase border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4 text-left w-24">Eye</th>
                            <th class="py-3 px-3">Sphere (SPH)</th>
                            <th class="py-3 px-3">Cylinder (CYL)</th>
                            <th class="py-3 px-3">Axis (°)</th>
                            <th class="py-3 px-3">Addition (ADD)</th>
                            <th class="py-3 px-3">Prism (Δ)</th>
                            <th class="py-3 px-3">Base</th>
                            <th class="py-3 px-3">VA (Dist)</th>
                            <th class="py-3 px-3">VA (Near)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <!-- OD (Right Eye) -->
                        <tr class="bg-white">
                            <td class="py-3.5 px-4 text-left font-extrabold text-blue-600 bg-blue-50/50">
                                Right (OD)
                            </td>
                            <td class="p-2">
                                <input type="number" step="0.25" name="od_sph" placeholder="0.00" class="w-20 p-2 bg-slate-50 border border-slate-200 rounded-lg text-center font-bold text-sm">
                            </td>
                            <td class="p-2">
                                <input type="number" step="0.25" name="od_cyl" placeholder="0.00" class="w-20 p-2 bg-slate-50 border border-slate-200 rounded-lg text-center font-bold text-sm">
                            </td>
                            <td class="p-2">
                                <input type="number" min="0" max="180" name="od_axis" placeholder="1-180" class="w-16 p-2 bg-slate-50 border border-slate-200 rounded-lg text-center font-bold text-sm">
                            </td>
                            <td class="p-2">
                                <input type="number" step="0.25" min="0" max="4.00" name="od_add" placeholder="+0.00" class="w-20 p-2 bg-slate-50 border border-slate-200 rounded-lg text-center font-bold text-sm">
                            </td>
                            <td class="p-2">
                                <input type="number" step="0.5" name="od_prism" placeholder="0.0" class="w-16 p-2 bg-slate-50 border border-slate-200 rounded-lg text-center text-xs">
                            </td>
                            <td class="p-2">
                                <select name="od_base" class="p-2 bg-slate-50 border border-slate-200 rounded-lg text-xs">
                                    <option value="">-</option>
                                    <option value="UP">UP</option>
                                    <option value="DOWN">DOWN</option>
                                    <option value="IN">IN</option>
                                    <option value="OUT">OUT</option>
                                </select>
                            </td>
                            <td class="p-2">
                                <input type="text" name="od_va_distance" value="6/6" class="w-16 p-2 bg-slate-50 border border-slate-200 rounded-lg text-center text-xs">
                            </td>
                            <td class="p-2">
                                <input type="text" name="od_va_near" value="N6" class="w-16 p-2 bg-slate-50 border border-slate-200 rounded-lg text-center text-xs">
                            </td>
                        </tr>

                        <!-- OS (Left Eye) -->
                        <tr class="bg-white">
                            <td class="py-3.5 px-4 text-left font-extrabold text-teal-600 bg-teal-50/50">
                                Left (OS)
                            </td>
                            <td class="p-2">
                                <input type="number" step="0.25" name="os_sph" placeholder="0.00" class="w-20 p-2 bg-slate-50 border border-slate-200 rounded-lg text-center font-bold text-sm">
                            </td>
                            <td class="p-2">
                                <input type="number" step="0.25" name="os_cyl" placeholder="0.00" class="w-20 p-2 bg-slate-50 border border-slate-200 rounded-lg text-center font-bold text-sm">
                            </td>
                            <td class="p-2">
                                <input type="number" min="0" max="180" name="os_axis" placeholder="1-180" class="w-16 p-2 bg-slate-50 border border-slate-200 rounded-lg text-center font-bold text-sm">
                            </td>
                            <td class="p-2">
                                <input type="number" step="0.25" min="0" max="4.00" name="os_add" placeholder="+0.00" class="w-20 p-2 bg-slate-50 border border-slate-200 rounded-lg text-center font-bold text-sm">
                            </td>
                            <td class="p-2">
                                <input type="number" step="0.5" name="os_prism" placeholder="0.0" class="w-16 p-2 bg-slate-50 border border-slate-200 rounded-lg text-center text-xs">
                            </td>
                            <td class="p-2">
                                <select name="os_base" class="p-2 bg-slate-50 border border-slate-200 rounded-lg text-xs">
                                    <option value="">-</option>
                                    <option value="UP">UP</option>
                                    <option value="DOWN">DOWN</option>
                                    <option value="IN">IN</option>
                                    <option value="OUT">OUT</option>
                                </select>
                            </td>
                            <td class="p-2">
                                <input type="text" name="os_va_distance" value="6/6" class="w-16 p-2 bg-slate-50 border border-slate-200 rounded-lg text-center text-xs">
                            </td>
                            <td class="p-2">
                                <input type="text" name="os_va_near" value="N6" class="w-16 p-2 bg-slate-50 border border-slate-200 rounded-lg text-center text-xs">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pupillary Distance (PD) & Clinical Notes -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Distance PD (mm)</label>
                <input type="number" step="0.5" min="45" max="80" name="pd_distance" placeholder="e.g. 64.0" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Near PD (mm)</label>
                <input type="number" step="0.5" min="45" max="80" name="pd_near" placeholder="e.g. 61.0" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
            </div>

            <div class="md:col-span-3">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Clinical Advice & Lens Recommendations</label>
                <textarea name="clinical_notes" rows="2" placeholder="e.g. Recommend high index 1.60 with anti-glare blue-filter coating for prolonged visual comfort." class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-blue-500 focus:bg-white transition"></textarea>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-100">
            <a href="{{ route('prescriptions.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50 transition">Cancel</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-600/30 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Save & Digitally Sign Prescription</span>
            </button>
        </div>

    </form>
</div>
@endsection

