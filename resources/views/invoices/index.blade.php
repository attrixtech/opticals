@extends('layouts.app', ['title' => 'GST Invoices', 'header' => 'GST Tax Invoices & Billing'])

@section('content')
<div class="space-y-6">

    <!-- Invoices List -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900">GST Tax Invoices</h3>
                <p class="text-xs text-slate-500 mt-0.5">Compliant Indian GST Tax Invoices with HSN, CGST, SGST, IGST breakdown.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-6">Invoice #</th>
                        <th class="py-3.5 px-6">Date</th>
                        <th class="py-3.5 px-6">Customer</th>
                        <th class="py-3.5 px-6 text-right">Taxable Value</th>
                        <th class="py-3.5 px-6 text-right">CGST</th>
                        <th class="py-3.5 px-6 text-right">SGST</th>
                        <th class="py-3.5 px-6 text-right">Total Invoice Value</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($invoices as $inv)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 px-6 font-mono font-bold text-rose-600">
                                <a href="{{ route('invoices.show', $inv->id) }}" class="hover:underline">{{ $inv->invoice_number }}</a>
                            </td>
                            <td class="py-4 px-6 text-slate-600">
                                {{ $inv->invoice_date->format('d M Y') }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900">{{ $inv->customer->name ?? 'Walk-In Customer' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $inv->customer_gstin ? 'GSTIN: ' . $inv->customer_gstin : 'B2C Retail' }}</div>
                            </td>
                            <td class="py-4 px-6 text-right font-mono">₹{{ number_format($inv->taxable_amount, 2) }}</td>
                            <td class="py-4 px-6 text-right font-mono text-slate-600">₹{{ number_format($inv->cgst_amount, 2) }}</td>
                            <td class="py-4 px-6 text-right font-mono text-slate-600">₹{{ number_format($inv->sgst_amount, 2) }}</td>
                            <td class="py-4 px-6 text-right font-mono font-extrabold text-slate-900 text-sm">₹{{ number_format($inv->total_amount, 2) }}</td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <a href="{{ route('invoices.show', $inv->id) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-700 text-xs font-semibold transition">Print Invoice</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">No GST tax invoices issued yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $invoices->links() }}
        </div>
    </div>

</div>
@endsection

