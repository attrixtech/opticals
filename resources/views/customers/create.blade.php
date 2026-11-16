@extends('layouts.app', ['title' => 'Register Customer', 'header' => 'Customer Registration'])

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-2xl border border-slate-200/80 shadow-sm p-8">

    <div class="mb-6 pb-5 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Patient & Customer Details</h2>
            <p class="text-xs text-slate-500 mt-0.5">Register walk-in client profile, contact information, and optical medical history.</p>
        </div>
        <a href="{{ route('customers.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">← Back to Customers</a>
    </div>

    <form method="POST" action="{{ route('customers.store') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Full Name *</label>
                <input type="text" name="name" required placeholder="e.g. Ramesh Patel" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Mobile Phone Number *</label>
                <input type="text" name="phone" required placeholder="e.g. +91 98200 12345" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email Address</label>
                <input type="email" name="email" placeholder="e.g. ramesh@example.com" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Gender</label>
                <select name="gender" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                    <option value="unspecified">Unspecified</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Date of Birth</label>
                <input type="date" name="date_of_birth" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Customer GSTIN (For B2B Billing)</label>
                <input type="text" name="gstin" placeholder="e.g. 27AAAAA0000A1Z5" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">City</label>
                <input type="text" name="city" value="Mumbai" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">State</label>
                <input type="text" name="state" value="Maharashtra" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">State Code (GST)</label>
                <input type="text" name="state_code" value="27" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
            </div>

            <div class="md:col-span-3">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Address</label>
                <textarea name="address" rows="2" placeholder="Street address..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-indigo-500 focus:bg-white transition"></textarea>
            </div>

            <div class="md:col-span-3">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Medical / Eye Conditions Notes</label>
                <textarea name="medical_notes" rows="2" placeholder="e.g. Prior cataract surgery in right eye, dry eye syndrome, contact lens allergy..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-indigo-500 focus:bg-white transition"></textarea>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-100">
            <a href="{{ route('customers.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50 transition">Cancel</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/30 transition">
                Create Customer Profile
            </button>
        </div>
    </form>

</div>
@endsection

