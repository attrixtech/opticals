@extends('layouts.app', ['title' => 'Tax Invoice ' . $invoice->invoice_number, 'header' => 'Tax Invoice: ' . $invoice->invoice_number])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Action Bar -->
    <div class="flex items-center justify-between print:hidden">
        <a href="{{ route('invoices.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">← Back to Invoices</a>
        <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-600/30 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            <span>Print Tax Invoice</span>
        </button>
    </div>

    <!-- Official GST Tax Invoice Format (Compliant with Central Goods & Services Tax Act) -->
    <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm text-slate-800 print:border-none print:shadow-none print:p-0">
        
        <!-- Header -->
        <div class="flex justify-between items-start pb-6 border-b-2 border-slate-900">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">VisionCraft Optical Superstore Pvt Ltd</h1>
                <p class="text-xs text-slate-600 mt-1 max-w-sm">
                    Shop 12-14, Ground Floor, Phoenix Millennium Mall, Lower Parel, Mumbai, Maharashtra 400013
                </p>
                <div class="mt-2 text-xs font-mono space-y-0.5">
                    <p><span class="font-bold">GSTIN:</span> 27AAACV1234F1Z5</p>
                    <p><span class="font-bold">PAN:</span> AAACV1234F • <span class="font-bold">State Code:</span> 27 (Maharashtra)</p>
                </div>
            </div>

            <div class="text-right">
                <span class="inline-block px-3 py-1 bg-slate-900 text-white font-black text-xs uppercase tracking-widest rounded-md mb-2">TAX INVOICE</span>
                <p class="font-mono font-bold text-sm text-slate-900">{{ $invoice->invoice_number }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Date: {{ $invoice->invoice_date->format('d/m/Y') }}</p>
                <p class="text-xs text-slate-500">Order Ref: {{ $invoice->salesOrder->order_number }}</p>
            </div>
        </div>

        <!-- Billed To Section -->
        <div class="grid grid-cols-2 gap-8 py-5 border-b border-slate-200 text-xs">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Details of Receiver (Billed To):</span>
                <p class="font-bold text-slate-900 text-sm">{{ $invoice->customer->name }}</p>
                <p class="text-slate-600 mt-0.5">{{ $invoice->customer->address ?? 'Mumbai, Maharashtra' }}</p>
                <p class="text-slate-600 mt-0.5">Phone: {{ $invoice->customer->phone }}</p>
                @if($invoice->customer_gstin)
                    <p class="font-mono font-bold text-slate-900 mt-1">Customer GSTIN: {{ $invoice->customer_gstin }}</p>
                @endif
            </div>

            <div class="text-right">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Place of Supply & Transport:</span>
                <p class="font-semibold text-slate-900">State: Maharashtra (Code: 27)</p>
                <p class="text-slate-600 mt-0.5">Counter Delivery & Optical Fitting</p>
                <p class="text-slate-600 mt-0.5">Reverse Charge (RCM): No</p>
            </div>
        </div>

        <!-- Line Items Table -->
        <div class="py-6 overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-700 uppercase font-bold border-b border-slate-300">
                    <tr>
                        <th class="py-2.5 px-3">#</th>
                        <th class="py-2.5 px-4">Description of Goods</th>
                        <th class="py-2.5 px-2 text-center">HSN</th>
                        <th class="py-2.5 px-2 text-center">Qty</th>
                        <th class="py-2.5 px-3 text-right">Rate (₹)</th>
                        <th class="py-2.5 px-3 text-right">Taxable (₹)</th>
                        <th class="py-2.5 px-3 text-right">CGST</th>
                        <th class="py-2.5 px-3 text-right">SGST</th>
                        <th class="py-2.5 px-4 text-right">Amount (₹)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 font-medium">
                    @foreach($invoice->salesOrder->items as $idx => $item)
                        <tr>
                            <td class="py-3 px-3 text-slate-500">{{ $idx + 1 }}</td>
                            <td class="py-3 px-4 font-bold text-slate-900">
                                {{ $item->item_name }}
                                <div class="text-[10px] text-slate-500 font-normal capitalize">{{ $item->item_type }}</div>
                            </td>
                            <td class="py-3 px-2 text-center font-mono">{{ $item->hsn_code }}</td>
                            <td class="py-3 px-2 text-center font-bold">{{ $item->quantity }}</td>
                            <td class="py-3 px-3 text-right font-mono">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="py-3 px-3 text-right font-mono">{{ number_format($item->taxable_amount, 2) }}</td>
                            <td class="py-3 px-3 text-right font-mono text-[11px]">{{ number_format($item->cgst_amount, 2) }} ({{ $item->tax_rate / 2 }}%)</td>
                            <td class="py-3 px-3 text-right font-mono text-[11px]">{{ number_format($item->sgst_amount, 2) }} ({{ $item->tax_rate / 2 }}%)</td>
                            <td class="py-3 px-4 text-right font-mono font-bold">{{ number_format($item->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Tax Totals Summary -->
        <div class="pt-4 border-t-2 border-slate-200 flex justify-end text-xs">
            <div class="w-80 space-y-2">
                <div class="flex justify-between text-slate-600">
                    <span>Total Taxable Value:</span>
                    <span class="font-mono font-bold">₹{{ number_format($invoice->taxable_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Central GST (CGST):</span>
                    <span class="font-mono">₹{{ number_format($invoice->cgst_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>State GST (SGST):</span>
                    <span class="font-mono">₹{{ number_format($invoice->sgst_amount, 2) }}</span>
                </div>
                @if($invoice->igst_amount > 0)
                    <div class="flex justify-between text-slate-600">
                        <span>Integrated GST (IGST):</span>
                        <span class="font-mono">₹{{ number_format($invoice->igst_amount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between font-black text-lg text-slate-900 pt-2 border-t-2 border-slate-900">
                    <span>Invoice Total:</span>
                    <span class="font-mono">₹{{ number_format($invoice->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Bank Details & Terms -->
        <div class="mt-8 pt-6 border-t border-slate-200 grid grid-cols-2 gap-8 text-[11px] text-slate-600">
            <div>
                <p class="font-bold text-slate-900 uppercase tracking-wider text-[10px]">Bank Details for Remittance:</p>
                <p class="mt-1 font-mono">Bank: HDFC Bank Ltd • A/C: 50200012345678</p>
                <p class="font-mono">IFSC: HDFC0000123 • Branch: Lower Parel, Mumbai</p>
                <p class="mt-2 text-slate-500 italic">Terms: Goods once sold will be serviced per manufacturer warranty. 1-Year coating warranty against peeling.</p>
            </div>

            <div class="text-right flex flex-col justify-between items-end">
                <span class="font-bold text-slate-900 text-xs">For VisionCraft Optical Superstore Pvt Ltd</span>
                <div class="mt-10 border-t border-slate-400 w-44 text-center pt-1 text-[10px] uppercase font-bold text-slate-500">
                    Authorized Signatory
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

